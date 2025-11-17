<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Colmena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerTratamiento extends Controller
{
    /**
     * Obtener el ID del dueño (apicultor) según quién está logueado.
     * - usuario  => usa su propio id
     * - colaborador => usa idusuario (dueño)
     */
    private function getOwnerId(): int
    {
        $user = Auth::user();

        if ($user->rol === 'colaborador') {
            return (int) $user->idusuario;
        }

        return (int) $user->id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownerId = $this->getOwnerId();

        $tratamientos = Tratamiento::with('colmena.apiario')
            ->where('idUsuario', $ownerId)
            // Solo colmenas activas, del dueño, y apiarios activos
            ->whereHas('colmena', function ($q) use ($ownerId) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', $ownerId)
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
        $ownerId = $this->getOwnerId();

        // Cargar las colmenas del dueño (apicultor) y que tengan estado activo
        $colmenas = Colmena::where('creadoPor', $ownerId)
                    ->where('estado', 'activo')
                    ->with('apiario')
                    ->get();

        return view('tratamiento.create', compact('colmenas'));
    }

    /**
     * VALIDACIÓN BÁSICA (store y update)
     */
    private function validarTratamiento(Request $request)
    {
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validarTratamiento($request);

        $ownerId = $this->getOwnerId();

        // Asegurar que la colmena pertenece al dueño y está activa
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        // ✅ VALIDACIÓN EXTRA: evitar duplicados (misma colmena + problema + tratamiento + fecha)
        $existe = Tratamiento::where('idUsuario', $ownerId)
            ->where('idColmena', $colmena->idColmena)
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

        $tratamiento = new Tratamiento();
        $tratamiento->problemaTratado         = $request->problemaTratado;
        $tratamiento->tratamientoAdministrado = $request->tratamientoAdministrado;
        $tratamiento->descripcion             = $request->descripcion;
        $tratamiento->fechaAdministracion     = $request->fechaAdministracion;
        $tratamiento->idUsuario               = $ownerId;             // siempre el dueño
        $tratamiento->idColmena               = $colmena->idColmena;  // colmena del dueño
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
        $ownerId = $this->getOwnerId();

        $tratamiento = Tratamiento::with('colmena.apiario')
            ->where('idTratamiento', $id)
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', $ownerId)
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
        $this->validarTratamiento($request);

        $ownerId = $this->getOwnerId();

        // ✅ VALIDACIÓN EXTRA TAMBIÉN EN UPDATE (evitar duplicados)
        $existe = Tratamiento::where('idUsuario', $ownerId)
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
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        // Validar que la colmena seleccionada pertenece al dueño y está activa
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
            ->firstOrFail();

        $tratamiento->problemaTratado         = $request->problemaTratado;
        $tratamiento->tratamientoAdministrado = $request->tratamientoAdministrado;
        $tratamiento->descripcion             = $request->descripcion;
        $tratamiento->fechaAdministracion     = $request->fechaAdministracion;
        $tratamiento->idColmena               = $colmena->idColmena;

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
        // El colaborador NO puede eliminar tratamientos
        if (Auth::user()->rol === 'colaborador') {
            abort(403, 'No tienes permiso para eliminar tratamientos.');
        }

        $ownerId = $this->getOwnerId();

        $tratamiento = Tratamiento::where('idTratamiento', $id)
            ->where('idUsuario', $ownerId)
            ->firstOrFail();

        $tratamiento->delete();

        return redirect()
            ->route('tratamiento.index')
            ->with('successdelete', 'Tratamiento eliminado exitosamente.');
    }
}
