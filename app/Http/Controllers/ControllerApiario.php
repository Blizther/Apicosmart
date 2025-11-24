<?php 

namespace App\Http\Controllers;

use App\Models\Apiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ControllerApiario extends Controller
{
    public function index()
    {
        // ID lógico del dueño (usuario o dueño del colaborador)
        $ownerId = Auth::user()->ownerId();

        // Solo apiarios activos del dueño
        $apiarios = Apiario::where('creadoPor', $ownerId)
                            ->where('estado', 'activo')
                            ->get();

        return view('apiario.index', compact('apiarios'));
    }

    public function create()
    {
        // Solo el usuario (apicultor) puede crear apiarios
        if (Auth::user()->rol !== 'usuario') {
            abort(403, 'No tienes permiso para crear apiarios.');
        }

        return view('apiario.create');
    }

    public function store(Request $request)
    {
        // Solo el usuario (apicultor) puede guardar apiarios
        if (Auth::user()->rol !== 'usuario') {
            abort(403, 'No tienes permiso para crear apiarios.');
        }

        $userId = Auth::id();

        $request->validate([
            'nombre' => [
                'required',
                'max:150',
                Rule::unique('apiario')->where(function ($query) use ($userId) {
                    return $query->where('creadoPor', $userId)
                                 ->where('estado', 'activo');
                }),
            ],
            'vegetacion' => 'string|max:100',
            'urlImagen' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'altitud' => 'numeric',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        date_default_timezone_set('America/La_Paz');
        $fecha = date('Y-m-d H:i:s');
        $user  = Auth::user()->id;

        $apiario = new Apiario();
        $apiario->nombre   = $request->nombre;
        $apiario->vegetacion = $request->vegetacion;
        $apiario->altitud  = $request->altitud;
        $apiario->latitud  = $request->latitud;
        $apiario->longitud = $request->longitud;
        $apiario->creadoPor = $user;
        $apiario->fechaCreacion = $fecha;

        if ($request->hasFile('urlImagen')) {
            $file = $request->file('urlImagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nombreArchivo);
            $apiario->urlImagen = 'uploads/' . $nombreArchivo;
        }

        // Estado activo por defecto
        $apiario->estado = 'activo';
        $apiario->save();

        return redirect()->to('/apiario')->with('success', 'Apiario creado exitosamente.');
    }

    public function edit(string $id)
    {
        // Solo el usuario (apicultor) puede editar apiarios
        if (Auth::user()->rol !== 'usuario') {
            abort(403, 'No tienes permiso para editar apiarios.');
        }

        $ownerId = Auth::user()->ownerId();

        // Asegurarnos que el apiario pertenece al dueño
        $apiario = Apiario::where('idApiario', $id)
                          ->where('creadoPor', $ownerId)
                          ->firstOrFail();

        return view('apiario.edit', compact('apiario'));
    }

    public function update(Request $request, string $id)
    {
        // Solo el usuario (apicultor) puede actualizar apiarios
        if (Auth::user()->rol !== 'usuario') {
            abort(403, 'No tienes permiso para actualizar apiarios.');
        }

        $request->validate([
            'nombre' => 'required|max:150',
            'vegetacion' => 'required|string|max:100',
            'urlImagen' => 'string|max:100',
            'altitud' => 'numeric',
            'estado' => 'required|string|max:8',
        ]);

        $ownerId = Auth::user()->ownerId();

        $apiario = Apiario::where('idApiario', $id)
                          ->where('creadoPor', $ownerId)
                          ->firstOrFail();

        $data = $request->all();
        $apiario->update($data);

        return redirect()->to('/apiario')->with('successedit', 'Apiario actualizado correctamente.');
    }

    /**
     * Eliminado lógico corregido
     * NO permite eliminar apiarios con colmenas activas
     */
    public function destroy(string $id)
    {
        // Solo el usuario (apicultor) puede eliminar apiarios
        if (Auth::user()->rol !== 'usuario') {
            abort(403, 'No tienes permiso para eliminar apiarios.');
        }

        $ownerId = Auth::user()->ownerId();

        $apiario = Apiario::where('idApiario', $id)
                          ->where('creadoPor', $ownerId)
                          ->firstOrFail();

        // 1) Verificar si tiene colmenas activas
        if ($apiario->colmenas()->where('estado', 'activo')->count() > 0) {
            return redirect()
                ->route('apiario.index')
                ->with('error', 'Debe eliminar primero las colmenas activas de este apiario antes de eliminarlo.');
        }

        // 2) Si no tiene colmenas activas, marcarlo como inactivo (eliminado lógico)
        $apiario->estado = 'inactivo';
        $apiario->save();

        return redirect()
            ->route('apiario.index')
            ->with('successdelete', 'Apiario eliminado exitosamente.');
    }

    public function vercolmenas(string $id)
    {
        $ownerId = Auth::user()->ownerId();

        // Solo apiarios del dueño, activos
        $apiario = Apiario::where('idApiario', $id)
                          ->where('creadoPor', $ownerId)
                          ->where('estado', 'activo')
                          ->firstOrFail();

        // Mostrar solo colmenas activas de ese apiario
        $colmenas = $apiario->colmenas()->where('estado', 'activo')->get();

        return view('apiario.verapiario', compact('apiario', 'colmenas'));
    }
}
