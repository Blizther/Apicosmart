<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\DispositivoFabricado;
use App\Models\Dispositivo;
use App\Models\LecturaSensor;
use App\Services\SensorAlertService;
use App\Notifications\AlertaSensorNotification;
use App\Models\User;
use App\Events\MetricUpdated;

class SensorIngestController extends Controller
{
    /**
     * Headers:
     *   X-DEVICE-SERIAL
     *   X-API-KEY
     * Body JSON:
     *   { "humedad": 00.00, "peso": 00.000, "temperatura": 00.00 }
     */
    public function store(Request $req, SensorAlertService $alertService)
    {
        // 1) Credenciales (mismos nombres; sin cambios)
        $serial = $req->header('X-DEVICE-SERIAL', $req->input('serial'));
        $apiKey = $req->header('X-API-KEY',       $req->input('api_key'));

        if (!$serial || !$apiKey) {
            return response()->json(['message' => 'No autorizado (faltan credenciales)'], 401);
        }

        // 2) Fabricado activo (mismos filtros; sin cambios)
        $fabricado = DispositivoFabricado::where('serial', $serial)
            ->where('estado', 1)
            ->first();

        if (!$fabricado) {
            return response()->json(['message' => 'Dispositivo no encontrado o inactivo'], 401);
        }

        // 3) API KEY contra hash (sin cambios)
        if (!Hash::check($apiKey, $fabricado->api_key_hash)) {
            return response()->json(['message' => 'API KEY inválida'], 401);
        }

        // 4) Vínculo activo en dispositivos (sin cambios)
        $vinculo = Dispositivo::where('dispositivo_fabricado_id', $fabricado->id)
            ->where('estado', 1)
            ->first();

        if (!$vinculo) {
            return response()->json(['message' => 'El dispositivo no está vinculado a una cuenta'], 401);
        }

        // 5) Validación suave de payload (mismos campos; no cambia nombres ni contrato)
        $data = $req->validate([
            'humedad'     => ['nullable','numeric'],
            'peso'        => ['nullable','numeric'],
            'temperatura' => ['nullable','numeric'],
        ]);

        // 6) Guardar lectura (mismas columnas)
        $lectura = new LecturaSensor();
        $lectura->dispositivo_id = $vinculo->id;
        $lectura->humedad        = array_key_exists('humedad', $data)     ? (float)$data['humedad']     : null;
        $lectura->peso           = array_key_exists('peso', $data)        ? (float)$data['peso']        : null;
        $lectura->temperatura    = array_key_exists('temperatura', $data) ? (float)$data['temperatura'] : null;
        $lectura->save();



$idColmena = $vinculo->idColmena;

if ($idColmena) {
    MetricUpdated::dispatch(
        (int) $idColmena,
        $lectura->temperatura,
        $lectura->humedad,
        $lectura->peso,
        now()->toDateTimeString()
    );
}


         // 1) Evaluar umbrales
    $alertas = $alertService->evaluar($lectura);

    if (!empty($alertas) && $alertService->puedeNotificar($vinculo)) {

        // 2) User asociado al dispositivo
        $userAsociado = $vinculo->user; // belongsTo idUser

        if ($userAsociado) {
            // 3) Resolver dueño lógico (si es colaborador, manda al dueño)
            $ownerId = $userAsociado->ownerId();
            $owner = User::find($ownerId);

            if ($owner && $owner->email) {
                $owner->notify(
                    new AlertaSensorNotification($vinculo, $lectura, $alertas)
                );
            }
        }
    }
        


        return response()->json([
            'ok'         => true,
            'mensaje'    => 'Lectura registrada correctamente',
            'id_lectura' => $lectura->id,
        ], 201);
    }
}
