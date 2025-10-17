<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\DispositivoFabricado;
use App\Models\Dispositivo;
use App\Models\LecturaSensor;

class SensorIngestController extends Controller
{
    /**
     * Ingesta desde ESP32.
     * Headers esperados:
     *   - X-DEVICE-SERIAL: serial del dispositivo
     *   - X-API-KEY: api key en texto plano (se valida contra el hash en dispositivos_fabricados)
     *
     * Body JSON (application/json):
     *   { "humedad": 00.00, "peso": 00.000, "temperatura": 00.00 }
     */
    public function store(Request $req)
    {
        // 1) Leer credenciales (header o body como fallback)
        $serial = $req->header('X-DEVICE-SERIAL', $req->input('serial'));
        $apiKey = $req->header('X-API-KEY',       $req->input('api_key'));

        if (!$serial || !$apiKey) {
            return response()->json(['message' => 'No autorizado (faltan credenciales)'], 401);
        }

        // 2) Buscar dispositivo fabricado ACTIVO
        $fabricado = DispositivoFabricado::where('serial', $serial)
            ->where('estado', 1)
            ->first();

        if (!$fabricado) {
            return response()->json(['message' => 'Dispositivo no encontrado o inactivo'], 401);
        }

        // 3) Validar API KEY contra el hash almacenado
        if (!Hash::check($apiKey, $fabricado->api_key_hash)) {
            return response()->json(['message' => 'API KEY inválida'], 401);
        }

        // 4) Debe existir un propietario (vínculo) activo
        $vinculo = Dispositivo::where('dispositivo_fabricado_id', $fabricado->id)
            ->where('estado', 1)
            ->first();

        if (!$vinculo) {
            return response()->json(['message' => 'El dispositivo no está vinculado a una cuenta'], 401);
        }

        // 5) Guardar lectura
        $lectura = new LecturaSensor();
        $lectura->dispositivo_id = $vinculo->id;
        $lectura->humedad        = $req->input('humedad');
        $lectura->peso           = $req->input('peso');
        $lectura->temperatura    = $req->input('temperatura');
        $lectura->save();

        // 6) Responder al cliente (ESP32)
        return response()->json([
            'ok'         => true,
            'mensaje'    => 'Lectura registrada correctamente',
            'id_lectura' => $lectura->id,
        ], 201);
    }
}
