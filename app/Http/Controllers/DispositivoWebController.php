<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\DispositivoFabricado;
use App\Models\LecturaSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispositivoWebController extends Controller
{
    // Listado y formulario de vinculación
    public function index()
    {
        $dispositivos = Dispositivo::with('fabricado')
            ->where('idUser', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('dispositivos.index', compact('dispositivos'));
    }

    // Vincular por serial (según la nueva lógica)
    public function store(Request $request)
    {
        $data = $request->validate([
            'serial' => 'required|string|max:64',
            'nombre' => 'nullable|string|max:120',
        ]);

        $serial = strtoupper($data['serial']);

        // 1) Debe existir en inventario
        $fab = DispositivoFabricado::where('serial', $serial)->first();
        if (!$fab) {
            return back()->withErrors(['serial' => 'El serial no existe en el inventario.'])->withInput();
        }

        // 2) No debe tener otro propietario
        if ($fab->vinculo && $fab->vinculo->idUser !== Auth::id()) {
            return back()->withErrors(['serial' => 'Este dispositivo ya pertenece a otra cuenta.'])->withInput();
        }

        // 3) Si ya lo tiene el mismo usuario, no duplicar
        if ($fab->vinculo && $fab->vinculo->idUser === Auth::id()) {
            return redirect()->route('mis.dispositivos')->with('ok','Este dispositivo ya estaba vinculado a tu cuenta.');
        }

        // 4) Crear vínculo
        $disp = Dispositivo::create([
            'idUser'                   => Auth::id(),
            'dispositivo_fabricado_id' => $fab->id,
            'nombre'                   => $data['nombre'] ?? null,
            'estado'                   => 1,
        ]);

        // 5) Activar fabricado si estaba inactivo
        if ((int)$fab->estado === 0) {
            $fab->estado = 1;
            $fab->save();
        }

        return redirect()->route('mis.dispositivos')->with('ok', 'Dispositivo vinculado.');
    }

    // Ver lecturas del dispositivo (solo propietario)
    public function show($id)
    {
        $dispositivo = Dispositivo::with('fabricado')
            ->where('id', $id)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        $lecturas = LecturaSensor::where('dispositivo_id', $dispositivo->id)
            ->orderByDesc('ts')
            ->paginate(25);

        return view('dispositivos.show', compact('dispositivo', 'lecturas'));
    }
}
