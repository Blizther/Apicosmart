<?php

namespace App\Http\Controllers;

use App\Models\DispositivoFabricado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class DispositivoFabricadoController extends Controller
{
    // Listar todos los dispositivos fabricados
    public function index()
    {
        $items = DispositivoFabricado::orderByDesc('id')->paginate(20);
        return view('dispositivos_fabricados.index', compact('items'));
    }

    // Formulario de alta
    public function create()
    {
        return view('dispositivos_fabricados.create');
    }

    // Guardar (muestra la API-KEY solo una vez)
    public function store(Request $request)
    {
        // 1) Validar: único en la tabla
        $request->validate([
            'serial' => 'required|string|max:64|unique:dispositivos_fabricados,serial',
        ], [
            'serial.unique' => 'Este serial ya está registrado en el inventario.',
            'serial.required' => 'El serial es obligatorio.',
        ]);

        // 2) Normalizar (para evitar duplicados por mayúsculas/minúsculas/espacios)
        $serial = strtoupper(trim($request->input('serial')));

        // 3) Generar API-KEY y crear
        $plainApiKey = Str::random(40);

        try {
            $item = DispositivoFabricado::create([
                'serial'       => $serial,
                'api_key_hash' => Hash::make($plainApiKey),
                'estado'       => 0, // inactivo al crear
            ]);
        } catch (QueryException $e) {
            // Si hubo condición de carrera (otro admin guardó el mismo serial un instante antes)
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['serial' => 'El serial ya existe.'])
                    ->withInput();
            }
            throw $e;
        }

        return redirect()
            ->route('fabricados.index')
            ->with('ok', 'Dispositivo creado: ' . $item->serial)
            ->with('api_key', $plainApiKey); // mostrar solo una vez
    }
}
