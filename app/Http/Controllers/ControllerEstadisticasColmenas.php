<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apiario;
use App\Models\Colmena;
use App\Models\Cosecha;
use App\Models\InspeccionColmena;
use App\Models\Tratamiento;
use App\Models\Alimentacion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ControllerEstadisticasColmenas extends Controller
{
    public function index()
    {
        $apiarios = Apiario::where('creadoPor', Auth::id())->get();
        return view('estadisticas.colmenas', compact('apiarios'));
    }

    private function filtrarPorFechas($query, $desde, $hasta, $campoFecha)
    {
        if ($desde && $hasta) {
            $query->whereBetween($campoFecha, [$desde, $hasta]);
        }
        return $query;
    }

    public function inspecciones(Request $request)
    {
        $colmenas = Colmena::with('inspecciones')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas($colmena->inspecciones(), $request->desde, $request->hasta, 'fechaCreacion')->count();
            $labels[] = $colmena->codigo;
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function cosecha(Request $request)
    {
        $colmenas = Colmena::with('cosechas')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $peso = $this->filtrarPorFechas($colmena->cosechas(), $request->desde, $request->hasta, 'fechaCosecha')->sum('peso');
            $labels[] = $colmena->codigo;
            $values[] = $peso;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function tratamientos(Request $request)
    {
        $colmenas = Colmena::with('tratamientos')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas($colmena->tratamientos(), $request->desde, $request->hasta, 'fechaAdministracion')->count();
            $labels[] = $colmena->codigo;
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function alimentaciones(Request $request)
    {
        $colmenas = Colmena::with('alimentaciones')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas($colmena->alimentaciones(), $request->desde, $request->hasta, 'fechaSuministracion')->count();
            $labels[] = $colmena->codigo;
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }
}
