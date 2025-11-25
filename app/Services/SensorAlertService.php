<?php

namespace App\Services;

use App\Models\LecturaSensor;
use App\Models\Dispositivo;

class SensorAlertService
{
    /**
     * Evalúa si algún valor de la lectura está fuera de los umbrales.
     */
    public function evaluar(LecturaSensor $lectura): array
    {
        $alertas = [];
        $umbrales = config('sensores');

        foreach (['humedad', 'peso', 'temperatura'] as $campo) {
            $valor = $lectura->$campo;
            if ($valor === null) continue;

            $min = $umbrales[$campo]['min'];
            $max = $umbrales[$campo]['max'];

            if ($valor < $min || $valor > $max) {
                $alertas[] = [
                    'campo' => $campo,
                    'valor' => $valor,
                    'min'   => $min,
                    'max'   => $max,
                ];
            }
        }

        return $alertas;
    }

    /**
     * Controla si debe enviarse una alerta usando un cooldown por dispositivo.
     *
     * No requiere Carbon (usa segundos).
     */
    public function puedeNotificar(Dispositivo $disp): bool
    {
        $key = "alerta_sensor_cooldown_{$disp->id}";

        // Si existe una alerta reciente -> no notificar
        if (cache()->has($key)) {
            return false;
        }

        // Tiempo en minutos desde config (o 30 por defecto)
        $minutos = config('sensores.cooldown_minutes', 30);

        // Guarda cooldown en segundos
        cache()->put($key, true, $minutos * 60);

        return true;
    }
}
