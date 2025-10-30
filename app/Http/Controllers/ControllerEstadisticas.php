<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apiario;
use App\Models\Colmena;
use App\Models\Cosecha;
use App\Models\Tratamiento;
use App\Models\Alimentacion;

use Illuminate\Support\Facades\DB;

class ControllerEstadisticas extends Controller
{
    public function index()
    {
        return view('estadisticas.index');
    }

    // 🔹 1. Cantidad de colmenas por apiario
    public function colmenasPorApiario()
    {
        $apiarios = Apiario::withCount('colmenas')->get();

        return response()->json([
            'labels' => $apiarios->pluck('nombre'),
            'values' => $apiarios->pluck('colmenas_count'),
        ]);
    }

    // 🔹 2. Peso total de cosecha por apiario
    public function pesoCosechaPorApiario()
    {
        $apiarios = Apiario::with(['colmenas.cosechas'])->get();

        $labels = [];
        $values = [];

        foreach ($apiarios as $apiario) {
            $pesoTotal = 0;
            foreach ($apiario->colmenas as $colmena) {
                $pesoTotal += $colmena->cosechas->sum('peso');
            }

            $labels[] = $apiario->nombre;
            $values[] = $pesoTotal;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    // 🔹 3. Cantidad de tratamientos por apiario
    public function tratamientosPorApiario()
    {
        $apiarios = Apiario::with(['colmenas.tratamientos'])->get();

        $labels = [];
        $values = [];

        foreach ($apiarios as $apiario) {
            $total = 0;
            foreach ($apiario->colmenas as $colmena) {
                $total += $colmena->tratamientos->count();
            }

            $labels[] = $apiario->nombre;
            $values[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    // 🔹 4. Cantidad de alimentaciones por apiario
    public function alimentacionesPorApiario()
    {
        $apiarios = Apiario::with(['colmenas.alimentaciones'])->get();

        $labels = [];
        $values = [];

        foreach ($apiarios as $apiario) {
            $total = 0;
            foreach ($apiario->colmenas as $colmena) {
                $total += $colmena->alimentaciones->count();
            }

            $labels[] = $apiario->nombre;
            $values[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
