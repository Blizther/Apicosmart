<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\LecturaSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DispositivoWebController extends Controller
{
    // Listado y formulario de alta
    public function index()
    {
        $user = Auth::user();
        $dispositivos = Dispositivo::where('idUser', $user->id)
            ->orderByDesc('id')
            ->get();

        return view('dispositivos.index', compact('dispositivos'));
    }

    // Registrar/vincular un dispositivo por serial
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'serial' => 'required|string|max:64|unique:dispositivos,serial',
            'nombre' => 'nullable|string|max:120',
        ]);

        $disp = new Dispositivo();
        $disp->idUser  = $user->id;
        $disp->serial  = $data['serial'];
        $disp->nombre  = $data['nombre'] ?? null;
        $disp->api_key = Str::random(40); // clave secreta del dispositivo
        $disp->estado  = 1;
        $disp->save();

        return redirect()->route('mis.dispositivos.show', $disp->id)
            ->with('ok', 'Dispositivo vinculado correctamente. Conserva tu API-KEY.');
    }

    // Detalle + lecturas (últimas 50)
    public function show($id)
    {
        $user = Auth::user();
        $disp = Dispositivo::where('idUser', $user->id)->findOrFail($id);

        $lecturas = LecturaSensor::where('dispositivo_id', $disp->id)
            ->orderByDesc('ts')
            ->limit(50)
            ->get();

        return view('dispositivos.show', compact('disp', 'lecturas'));
    }
}
