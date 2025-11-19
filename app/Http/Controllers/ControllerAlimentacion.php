<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimentacion;
use App\Models\Colmena;
use Illuminate\Support\Facades\Auth;

class ControllerAlimentacion extends Controller
{
    /**
     * Obtener el ID del dueño (apicultor) según quién está logueado.
     * - usuario      => usa su propio id
     * - colaborador  => usa idusuario (dueño)
     */
    private function getOwnerId(): int
    {
        $user = Auth::user();

        if ($user->rol === 'colaborador') {
            return (int) $user->idusuario;
        }

        return (int) $user->id;
    }

    // LISTA
    public function index()
    {
        $ownerId = $this->getOwnerId();

        $alimentaciones = Alimentacion::with(['colmena.apiario'])
            ->where('idUsuario', $ownerId)
            // Solo colmenas activas del dueño y apiarios activos
            ->whereHas('colmena', function ($q) use ($ownerId) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', $ownerId)
                  ->whereHas('apiario', function ($q2) {
                      $q2->where('estado', 'activo');
                  });
            })
            ->orderBy('fechaSuministracion', 'desc')
            ->orderBy('idAlimentacion', 'desc')
            ->get();

        return view('alimentacion.index', compact('alimentaciones'));
    }

    // FORM CREAR
    public function create()
    {
        $ownerId = $this->getOwnerId();

        $colmenas = Colmena::where('creadoPor', $ownerId)
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('alimentacion.create', compact('colmenas'));
    }

    /**
     * Validación común (store + update)
     */
   private function validarAlimentacion(Request $request)
{
    // 1) VALIDACIÓN BASE (sin límites raros visibles)
    $request->validate([
        'tipoAlimento'        => 'required|string|max:100',
        'cantidad'            => 'required|numeric|min:0.1',
        'unidadMedida'        => 'required|in:gr,Kg,ml,L',
        'motivo'              => 'required|string|max:255',
        'fechaSuministracion' => 'required|date',
        'idColmena'           => 'required|numeric|min:1|exists:colmena,idColmena',
        'observaciones'       => 'nullable|string|max:255',
    ], [
        'tipoAlimento.required'        => 'El tipo de alimento es obligatorio.',
        'cantidad.required'            => 'La cantidad es obligatoria.',
        'cantidad.numeric'             => 'La cantidad debe ser un valor numérico.',
        'cantidad.min'                 => 'La cantidad debe ser mayor a 0.',
        'unidadMedida.required'        => 'La unidad de medida es obligatoria.',
        'unidadMedida.in'              => 'La unidad de medida seleccionada no es válida.',
        'motivo.required'              => 'El motivo es obligatorio.',
        'motivo.max'                   => 'El motivo no debe exceder los 255 caracteres.',
        'fechaSuministracion.required' => 'La fecha de suministración es obligatoria.',
        'fechaSuministracion.date'     => 'La fecha de suministración debe ser una fecha válida.',
        'idColmena.required'           => 'La colmena es obligatoria.',
        'idColmena.exists'             => 'La colmena seleccionada no es válida.',
        'observaciones.max'            => 'Las observaciones no deben exceder los 255 caracteres.',
    ]);

    // 2) VALIDACIÓN "REAL" SEGÚN LA UNIDAD DE MEDIDA
    $cantidad = (float) $request->cantidad;
    $unidad   = $request->unidadMedida;

    // Límites máximos por evento de alimentación y por colmena,
    // basados en rangos razonables de manejo apícola
    $maxPorUnidad = [
        'gr' => 900, // máximo 900 gramos de torta/proteína en una toma
        'Kg' => 10,  // máximo 10 kg en una sola alimentación
        'ml' => 900, // máximo 900 mililitros (≈ 1 litro) en ml
        'L'  => 5,   // máximo 5 litros de jarabe/agua vitaminada
    ];

    if (isset($maxPorUnidad[$unidad]) && $cantidad > $maxPorUnidad[$unidad]) {

        $unidadTexto = match ($unidad) {
            'gr' => 'gramos',
            'Kg' => 'kilogramos',
            'ml' => 'mililitros',
            'L'  => 'litros',
            default => 'unidad',
        };

        $max = $maxPorUnidad[$unidad];

        return back()
            ->withInput()
            ->withErrors([
                'cantidad' => "Para la unidad de medida '{$unidadTexto}', la cantidad máxima permitida es {$max} {$unidadTexto}.",
            ]);
    }

    // 3) VALIDACIÓN TÉCNICA POR SI ACASO (protege el numeric(4,1))
    // Solo se ejecuta si la unidad no estaba en el arreglo o si algún día pones
    // un máximo mayor que el que soporta la BD.
    $maxBD = 999.9;
    if ($cantidad > $maxBD) {
        return back()
            ->withInput()
            ->withErrors([
                'cantidad' => 'La cantidad ingresada es demasiado alta para ser registrada en el sistema.',
            ]);
    }

    return null; // Todo OK
}


    // GUARDAR NUEVO
    public function store(Request $request)
    {
        if ($respuesta = $this->validarAlimentacion($request)) {
            return $respuesta; // vuelve con errores si falló la validación extra
        }

        $ownerId = $this->getOwnerId();

        // Asegurar que la colmena pertenece al dueño y está activa
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        date_default_timezone_set('America/La_Paz');

        $alimentacion = new Alimentacion();
        $alimentacion->tipoAlimento        = $request->tipoAlimento;
        $alimentacion->cantidad            = $request->cantidad;
        $alimentacion->unidadMedida        = $request->unidadMedida;
        $alimentacion->motivo              = $request->motivo;
        $alimentacion->fechaSuministracion = $request->fechaSuministracion;
        $alimentacion->observaciones       = $request->observaciones;
        $alimentacion->idUsuario           = $ownerId;           // siempre el dueño
        $alimentacion->idColmena           = $colmena->idColmena;
        $alimentacion->save();

        return redirect()
            ->route('alimentacion.index')
            ->with('success', 'Alimentación registrada exitosamente.');
    }

    // FORM EDITAR
    public function edit($id)
    {
        $ownerId = $this->getOwnerId();

        $alimentacion = Alimentacion::with('colmena.apiario')
            ->where('idAlimentacion', $id)
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', $ownerId)
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('alimentacion.edit', compact('alimentacion', 'colmenas'));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        if ($respuesta = $this->validarAlimentacion($request)) {
            return $respuesta; // vuelve con errores si falló la validación extra
        }

        $ownerId = $this->getOwnerId();

        $alimentacion = Alimentacion::where('idAlimentacion', $id)
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        // Validar que la colmena seleccionada pertenece al dueño y está activa
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        $alimentacion->tipoAlimento        = $request->tipoAlimento;
        $alimentacion->cantidad            = $request->cantidad;
        $alimentacion->unidadMedida        = $request->unidadMedida;
        $alimentacion->motivo              = $request->motivo;
        $alimentacion->fechaSuministracion = $request->fechaSuministracion;
        $alimentacion->observaciones       = $request->observaciones;
        $alimentacion->idColmena           = $colmena->idColmena;
        $alimentacion->save();

        return redirect()
            ->route('alimentacion.index')
            ->with('successedit', 'Alimentación actualizada correctamente.');
    }

    // ELIMINAR
    public function destroy($id)
    {
        // El colaborador NO puede eliminar alimentaciones
        if (Auth::user()->rol === 'colaborador') {
            abort(403, 'No tienes permiso para eliminar registros de alimentación.');
        }

        $ownerId = $this->getOwnerId();

        $alimentacion = Alimentacion::where('idAlimentacion', $id)
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        $alimentacion->delete();

        return redirect()
            ->route('alimentacion.index')
            ->with('successdelete', 'Alimentación eliminada correctamente.');
    }
}
