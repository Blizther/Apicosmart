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
            // Solo colmenas activas y apiarios activos
            ->whereHas('colmena', function ($q) {
                $q->where('estado', 'activo')
                  ->whereHas('apiario', function ($q2) {
                      $q2->where('estado', 'activo');
                  });
            })
            ->orderBy('fechaAdministracion', 'desc')
            ->orderBy('idTratamiento', 'desc')
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
                    ->with('apiario')
                    ->get();

        return view('tratamiento.create', compact('colmenas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDACIÓN BÁSICA
        $request->validate([
            'problemaTratado'        => 'required|string|max:255',
            'tratamientoAdministrado'=> 'required|string|max:255',
            'descripcion'            => 'nullable|string',
            'fechaAdministracion'    => 'required|date',
            'idColmena'              => 'required|numeric|min:1',
        ], [
            'problemaTratado.required'         => 'El problema tratado es obligatorio.',
            'problemaTratado.max'              => 'El problema tratado no debe exceder los 255 caracteres.',
            'tratamientoAdministrado.required' => 'El tratamiento administrado es obligatorio.',
            'tratamientoAdministrado.max'      => 'El tratamiento administrado no debe exceder los 255 caracteres.',
            'fechaAdministracion.required'     => 'La fecha de administración es obligatoria.',
            'fechaAdministracion.date'         => 'La fecha de administración debe ser una fecha válida.',
            'idColmena.required'               => 'La colmena es obligatoria.',
            'idColmena.numeric'                => 'La colmena debe ser un valor numérico válido.',
            'idColmena.min'                    => 'La colmena seleccionada no es válida.',
        ]);

        // ✅ VALIDACIÓN EXTRA: evitar duplicados (misma colmena + problema + tratamiento + fecha)
        $existe = Tratamiento::where('idUsuario', Auth::id())
            ->where('idColmena', $request->idColmena)
            ->where('problemaTratado', $request->problemaTratado)
            ->where('tratamientoAdministrado', $request->tratamientoAdministrado)
            ->whereDate('fechaAdministracion', $request->fechaAdministracion)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'tratamientoAdministrado' =>
                        'Ya registraste este tratamiento para esta colmena, con el mismo problema y en la misma fecha.',
                ]);
        }

        date_default_timezone_set('America/La_Paz');
        $user = Auth::id();

        $tratamiento = new Tratamiento();
        $tratamiento->problemaTratado         = $request->problemaTratado;
        $tratamiento->tratamientoAdministrado = $request->tratamientoAdministrado;
        $tratamiento->descripcion             = $request->descripcion;
        $tratamiento->fechaAdministracion     = $request->fechaAdministracion;
        $tratamiento->idUsuario               = $user;
        $tratamiento->idColmena               = $request->idColmena;
        $tratamiento->save();

        return redirect()
            ->route('tratamiento.index')
            ->with('success', 'Tratamiento registrado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tratamiento = Tratamiento::with('colmena.apiario')
            ->where('idTratamiento', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->with('apiario')
            ->get();

        return view('tratamiento.edit', compact('tratamiento', 'colmenas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // VALIDACIÓN BÁSICA
        $request->validate([
            'problemaTratado'        => 'required|string|max:255',
            'tratamientoAdministrado'=> 'required|string|max:255',
            'descripcion'            => 'nullable|string',
            'fechaAdministracion'    => 'required|date',
            'idColmena'              => 'required|numeric|min:1',
        ]);

        // ✅ VALIDACIÓN EXTRA TAMBIÉN EN UPDATE:
        // No permitir que quede igual a OTRO registro distinto (evitar duplicado)
        $existe = Tratamiento::where('idUsuario', Auth::id())
            ->where('idColmena', $request->idColmena)
            ->where('problemaTratado', $request->problemaTratado)
            ->where('tratamientoAdministrado', $request->tratamientoAdministrado)
            ->whereDate('fechaAdministracion', $request->fechaAdministracion)
            ->where('idTratamiento', '!=', $id)   // excluir el que estamos editando
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'tratamientoAdministrado' =>
                        'Ya existe otro tratamiento igual para esta colmena con el mismo problema y en esa fecha.',
                ]);
        }

        $tratamiento = Tratamiento::where('idTratamiento', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $tratamiento->problemaTratado         = $request->problemaTratado;
        $tratamiento->tratamientoAdministrado = $request->tratamientoAdministrado;
        $tratamiento->descripcion             = $request->descripcion;
        $tratamiento->fechaAdministracion     = $request->fechaAdministracion;
        $tratamiento->idColmena               = $request->idColmena;
        $tratamiento->save();

        return redirect()
            ->route('tratamiento.index')
            ->with('successedit', 'Tratamiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tratamiento = Tratamiento::where('idTratamiento', $id)
            ->where('idUsuario', Auth::id())
            ->firstOrFail();

        $tratamiento->delete();

        return redirect()
            ->route('tratamiento.index')
            ->with('successdelete', 'Tratamiento eliminado exitosamente.');
    }
}
