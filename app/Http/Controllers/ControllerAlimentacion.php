<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimentacion;
use App\Models\Colmena;
use Illuminate\Support\Facades\Auth;

class ControllerAlimentacion extends Controller
{
    // LISTA
    public function index()
    {
        $alimentaciones = Alimentacion::with(['colmena.apiario'])
            ->where('idUsuario', Auth::id())
            // Solo colmenas activas y apiarios activos
            ->whereHas('colmena', function ($q) {
                $q->where('estado', 'activo')
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
        $idUser = Auth::id();

        $colmenas = Colmena::where('creadoPor', $idUser)
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

        // Reglas adicionales según unidad de medida
        $cantidad = (float) $request->cantidad;
        $unidad   = $request->unidadMedida;

        // Límites razonables por unidad (ajustables)
        $maxPorUnidad = [
            'gr' => 10000, // 10 000 g = 10 kg
            'Kg' => 10,    // 10 kg máximo
            'ml' => 2000,  // 2 L
            'L'  => 5,     // 5 L máximo
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
                    'cantidad' => "Para la unidad de medida '{$unidadTexto}' la cantidad máxima permitida es {$max}.",
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

        date_default_timezone_set('America/La_Paz');
        $user = Auth::id();

        $alimentacion = new Alimentacion();
        $alimentacion->tipoAlimento        = $request->tipoAlimento;
        $alimentacion->cantidad            = $request->cantidad;
        $alimentacion->unidadMedida        = $request->unidadMedida;
        $alimentacion->motivo              = $request->motivo;
        $alimentacion->fechaSuministracion = $request->fechaSuministracion;
        $alimentacion->observaciones       = $request->observaciones;
        $alimentacion->idUsuario           = $user;
        $alimentacion->idColmena           = $request->idColmena;
        $alimentacion->save();

        return redirect()
            ->route('alimentacion.index')
            ->with('success', 'Alimentación registrada exitosamente.');
    }

    // FORM EDITAR
    public function edit($id)
    {
        $alimentacion = Alimentacion::with('colmena.apiario')
            ->where('idAlimentacion', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', Auth::id())
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

        $alimentacion = Alimentacion::where('idAlimentacion', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $alimentacion->tipoAlimento        = $request->tipoAlimento;
        $alimentacion->cantidad            = $request->cantidad;
        $alimentacion->unidadMedida        = $request->unidadMedida;
        $alimentacion->motivo              = $request->motivo;
        $alimentacion->fechaSuministracion = $request->fechaSuministracion;
        $alimentacion->observaciones       = $request->observaciones;
        $alimentacion->idColmena           = $request->idColmena;
        $alimentacion->save();

        return redirect()
            ->route('alimentacion.index')
            ->with('successedit', 'Alimentación actualizada correctamente.');
    }

    // ELIMINAR
    public function destroy($id)
    {
        $alimentacion = Alimentacion::where('idAlimentacion', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $alimentacion->delete();

        return redirect()
            ->route('alimentacion.index')
            ->with('successdelete', 'Alimentación eliminada correctamente.');
    }
}
