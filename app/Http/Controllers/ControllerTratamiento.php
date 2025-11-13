<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Colmena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerTratamiento extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tratamientos = Tratamiento::with('colmena.apiario')
            ->where('idUsuario', Auth::id())

            // Filtrar SOLO tratamientos cuya colmena está activa
            // y cuyo apiario también está activo
            ->whereHas('colmena', function ($q) {
                $q->where('estado', 'activo')
                  ->whereHas('apiario', function ($q2) {
                      $q2->where('estado', 'activo');
                  });
            })

            ->orderBy('fechaAdministracion', 'desc')
            ->get();

        return view('tratamiento.index', compact('tratamientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cargar las colmenas del usuario autenticado y que tengan estado activo
        $idUser = Auth::id(); // ID del usuario logueado
        $colmenas = Colmena::where('creadoPor', $idUser)
                    ->where('estado', 'activo')
                    ->get();

        return view('tratamiento.create', compact('colmenas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'problemaTratado' => 'required|string|max:255',
            'tratamientoAdministrado' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fechaAdministracion' => 'required|date',
            'idColmena' => 'required|numeric|min:1',
        ],
        [
            'problemaTratado.required' => 'El problema tratado es obligatorio.',
            'problemaTratado.max' => 'El problema tratado no debe exceder los 255 caracteres.',
            'tratamientoAdministrado.required' => 'El tratamiento administrado es obligatorio.',
            'tratamientoAdministrado.max' => 'El tratamiento administrado no debe exceder los 255 caracteres.',
            'fechaAdministracion.required' => 'La fecha de administración es obligatoria.',
            'fechaAdministracion.date' => 'La fecha de administración debe ser una fecha válida.',
            'idColmena.required' => 'La colmena es obligatoria.',
            'idColmena.numeric' => 'La colmena debe ser un valor numérico.',
            'idColmena.min' => 'La colmena seleccionada no es válida.',
        ]);

        date_default_timezone_set('America/Caracas');
        $fecha = date('Y-m-d H:i:s');
        $user = Auth::user()->id;

        $tratamiento = new Tratamiento();
        $tratamiento->problemaTratado = $request->problemaTratado;
        $tratamiento->tratamientoAdministrado = $request->tratamientoAdministrado;
        $tratamiento->descripcion = $request->descripcion;
        $tratamiento->fechaAdministracion = $request->fechaAdministracion;
        $tratamiento->idUsuario = $user;
        $tratamiento->idColmena = $request->idColmena;
        $tratamiento->save();

        return redirect()->to('/tratamiento')->with('success', 'Tratamiento registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        return view('tratamiento.edit', compact('tratamiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'problemaTratado' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fechaAdministracion' => 'required|date',
            'idColmena' => 'required|numeric|min:1',
        ]);

        $tratamiento = Tratamiento::findOrFail($id);
        $tratamiento->problemaTratado = $request->problemaTratado;
        $tratamiento->descripcion = $request->descripcion;
        $tratamiento->fechaAdministracion = $request->fechaAdministracion;
        $tratamiento->idColmena = $request->idColmena;
        $tratamiento->save();

        // Ojo: aquí ya lo tenías con /tratamientos (plural)
        return redirect()->to('/tratamientos')->with('success', 'Tratamiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $tratamiento->delete();

        // Igual que arriba, respeté tu /tratamientos (plural)
        return redirect()->to('/tratamientos')->with('successdelete', 'Tratamiento eliminado exitosamente.');
    }

}
