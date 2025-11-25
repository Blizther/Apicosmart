<?php

namespace App\Http\Controllers;

use App\Models\Cosecha;
use App\Models\Colmena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerCosecha extends Controller
{
    /**
     * Obtener el ID del dueño (apicultor) según quién está logueado.
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

        $cosechas = Cosecha::with('colmena.apiario')
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) use ($ownerId) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', $ownerId)
                      // ✅ asegura que tenga apiario activo
                      ->whereHas('apiario', function ($q) {
                          $q->where('estado', 'activo');
                      });
            })
            ->orderBy('fechaCosecha', 'desc')
            ->orderBy('idCosecha', 'desc')
            ->paginate(10); // ✅ paginación

        return view('cosecha.index', compact('cosechas'));
    }

    // FORM CREAR
    public function create()
    {
        $ownerId = $this->getOwnerId();

        $colmenas = Colmena::where('creadoPor', $ownerId)
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('cosecha.create', compact('colmenas'));
    }

    // VALIDACIÓN COMÚN (store + update)
    private function validarCosecha(Request $request)
    {
        $request->validate(
            [
                'peso'          => 'required|numeric|min:0.1|max:50',
                'estadoMiel'    => 'required|string|max:20',
                'fechaCosecha'  => 'required|date',
                'idColmena'     => 'required|numeric|min:1',
                'observaciones' => 'nullable|string|max:255',
            ],
            [
                'peso.required' => 'El peso de la cosecha es obligatorio.',
                'peso.numeric'  => 'El peso debe ser numérico.',
                'peso.min'      => 'El peso debe ser mayor a 0.',
                'peso.max'      => 'El peso máximo permitido por colmena es 50 kg.',
                'estadoMiel.required'   => 'El estado de la miel es obligatorio.',
                'estadoMiel.string'     => 'El estado de la miel no es válido.',
                'estadoMiel.max'        => 'El estado de la miel no debe exceder 20 caracteres.',
                'fechaCosecha.required' => 'La fecha de cosecha es obligatoria.',
                'fechaCosecha.date'     => 'La fecha de cosecha no es válida.',
                'idColmena.required'    => 'Debe seleccionar una colmena.',
                'idColmena.numeric'     => 'La colmena no es válida.',
                'idColmena.min'         => 'La colmena no es válida.',
                'observaciones.string'  => 'Las observaciones no son válidas.',
                'observaciones.max'     => 'Las observaciones no deben exceder 255 caracteres.',
            ]
        );
    }

    // GUARDAR NUEVA COSECHA
    public function store(Request $request)
    {
        $this->validarCosecha($request);

        $ownerId = $this->getOwnerId();

        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        date_default_timezone_set('America/La_Paz');

        $cosecha = new Cosecha();
        $cosecha->idUsuario     = $ownerId;
        $cosecha->peso          = $request->peso;
        $cosecha->estadoMiel    = $request->estadoMiel;
        $cosecha->fechaCosecha  = $request->fechaCosecha;
        $cosecha->idColmena     = $colmena->idColmena;
        $cosecha->observaciones = $request->observaciones;
        $cosecha->estado        = 'activo';
        $cosecha->save();

        return redirect()
            ->route('cosechas.index')
            ->with('success', 'Cosecha registrada exitosamente.');
    }

    public function show($id)
    {
        //
    }

    // FORM EDITAR
    public function edit($id)
    {
        $ownerId = $this->getOwnerId();

        $cosecha = Cosecha::with('colmena.apiario')
            ->where('idCosecha', $id)
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) use ($ownerId) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', $ownerId);
            })
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', $ownerId)
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('cosecha.edit', compact('cosecha', 'colmenas'));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $this->validarCosecha($request);

        $ownerId = $this->getOwnerId();

        $cosecha = Cosecha::where('idCosecha', $id)
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) use ($ownerId) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', $ownerId);
            })
            ->firstOrFail();

        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        $cosecha->peso          = $request->peso;
        $cosecha->estadoMiel    = $request->estadoMiel;
        $cosecha->fechaCosecha  = $request->fechaCosecha;
        $cosecha->idColmena     = $colmena->idColmena;
        $cosecha->observaciones = $request->observaciones;
        $cosecha->save();

        return redirect()
            ->route('cosechas.index')
            ->with('successedit', 'Cosecha actualizada exitosamente.');
    }

    // ELIMINAR (lógico) – SOLO el usuario dueño puede eliminar
    public function destroy($id)
    {
        if (Auth::user()->rol === 'colaborador') {
            abort(403, 'No tienes permiso para eliminar cosechas.');
        }

        $ownerId = $this->getOwnerId();

        $cosecha = Cosecha::where('idCosecha', $id)
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) use ($ownerId) {
                $query->where('creadoPor', $ownerId);
            })
            ->firstOrFail();

        $cosecha->estado = 'inactivo';
        $cosecha->save();

        return redirect()
            ->route('cosechas.index')
            ->with('successdelete', 'Cosecha eliminada exitosamente.');
    }
}
