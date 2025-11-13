<?php

namespace App\Http\Controllers;

use App\Models\Cosecha;
use App\Models\Colmena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerCosecha extends Controller
{
    // LISTA
    public function index()
    {
        $cosechas = Cosecha::with('colmena.apiario')
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', Auth::id());
            })
            ->orderBy('fechaCosecha', 'desc')
            ->orderBy('idCosecha', 'desc')
            ->get();

        return view('cosecha.index', compact('cosechas'));
    }

    // FORM CREAR
    public function create()
    {
        $colmenas = Colmena::where('creadoPor', Auth::id())
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
                'peso'          => 'required|numeric|min:0.1|max:50', // 0.1–50 kg
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

        date_default_timezone_set('America/La_Paz');

        $cosecha = new Cosecha();
        $cosecha->idUsuario     = Auth::id();
        $cosecha->peso          = $request->peso;
        $cosecha->estadoMiel    = $request->estadoMiel;
        $cosecha->fechaCosecha  = $request->fechaCosecha;
        $cosecha->idColmena     = $request->idColmena;
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
        $cosecha = Cosecha::with('colmena.apiario')
            ->where('idCosecha', $id)
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', Auth::id());
            })
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('cosecha.edit', compact('cosecha', 'colmenas'));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $this->validarCosecha($request);

        $cosecha = Cosecha::where('idCosecha', $id)
            ->where('estado', 'activo')
            ->whereHas('colmena', function ($query) {
                $query->where('estado', 'activo')
                      ->where('creadoPor', Auth::id());
            })
            ->firstOrFail();

        $cosecha->peso          = $request->peso;
        $cosecha->estadoMiel    = $request->estadoMiel;
        $cosecha->fechaCosecha  = $request->fechaCosecha;
        $cosecha->idColmena     = $request->idColmena;
        $cosecha->observaciones = $request->observaciones;

        $cosecha->save();

        return redirect()
            ->route('cosechas.index')
            ->with('successedit', 'Cosecha actualizada exitosamente.');
    }

    // ELIMINAR (lógico)
    public function destroy($id)
    {
        $cosecha = Cosecha::where('idCosecha', $id)
            ->where('estado', 'activo')
            ->firstOrFail();

        $cosecha->estado = 'inactivo';
        $cosecha->save();

        return redirect()
            ->route('cosechas.index')
            ->with('successdelete', 'Cosecha eliminada exitosamente.');
    }
}
