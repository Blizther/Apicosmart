<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use App\Models\LecturaSensor;
use Illuminate\Http\Request;

class SensorIngestController extends Controller
{
    // POST /api/v1/sensores
    public function store(Request $req)
    {
        // 1) Autenticación por encabezados o body
        $serial = $req->header('X-DEVICE-SERIAL', $req->input('serial'));
        $apiKey = $req->header('X-API-KEY',       $req->input('api_key'));

        if (!$serial || !$apiKey) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        $disp = Dispositivo::query()
            ->where('serial', $serial)
            ->where('api_key', $apiKey)
            ->where('estado', 1)
            ->first();

        if (!$disp) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        // 2) Validar payload (solo los 3 sensores)
        $data = $req->validate([
            'ts'          => 'sometimes|date',
            'humedad'     => 'sometimes|numeric',
            'peso'        => 'sometimes|numeric',
            'temperatura' => 'sometimes|numeric',
        ]);

        // 3) Insertar lectura
        $lectura = new LecturaSensor();
        $lectura->dispositivo_id = $disp->id;
        if (isset($data['ts'])) $lectura->ts = $data['ts'];
        if (array_key_exists('humedad', $data))     $lectura->humedad     = $data['humedad'];
        if (array_key_exists('peso', $data))        $lectura->peso        = $data['peso'];
        if (array_key_exists('temperatura', $data)) $lectura->temperatura = $data['temperatura'];
        $lectura->save();

        return response()->json([
            'ok' => true,
            'dispositivo_id' => $disp->id,
            'lectura_id' => $lectura->id,
            'ts' => $lectura->ts,
        ], 201);
    }
}
