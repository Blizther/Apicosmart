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
        $apiarios = Apiario::where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->get();

        return view('estadisticas.colmenas', compact('apiarios'));
    }

    private function filtrarPorFechas($query, $desde, $hasta, $campoFecha)
    {
        if ($desde && $hasta) {
            $query->whereBetween($campoFecha, [$desde, $hasta]);
        }
        return $query;
    }

    /**
     * Construye la etiqueta que se mostrará en el gráfico:
     * "NombreApiario - CodigoColmena"
     */
    private function etiquetaColmena(Colmena $colmena): string
    {
        $apiario = $colmena->apiario;
        $nombreApiario = $apiario ? $apiario->nombre : 'Sin apiario';
        $codigo = $colmena->codigo ?? 'Sin código';

        return $nombreApiario . ' - ' . $codigo;
    }

    public function inspecciones(Request $request)
    {
        $colmenas = Colmena::with(['inspecciones', 'apiario'])
            ->where('estado', 'activo')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', Auth::id());
            })
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas(
                $colmena->inspecciones(),
                $request->desde,
                $request->hasta,
                'fechaInspeccion' 
            )->count();

            $labels[] = $this->etiquetaColmena($colmena);
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function cosecha(Request $request)
    {
        $colmenas = Colmena::with(['cosechas', 'apiario'])
            ->where('estado', 'activo')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', Auth::id());
            })
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $peso = $this->filtrarPorFechas(
                $colmena->cosechas(),
                $request->desde,
                $request->hasta,
                'fechaCosecha'
            )->sum('peso');

            $labels[] = $this->etiquetaColmena($colmena);
            $values[] = $peso;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function tratamientos(Request $request)
    {
        $colmenas = Colmena::with(['tratamientos', 'apiario'])
            ->where('estado', 'activo')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', Auth::id());
            })
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas(
                $colmena->tratamientos(),
                $request->desde,
                $request->hasta,
                'fechaAdministracion'
            )->count();

            $labels[] = $this->etiquetaColmena($colmena);
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }

    public function alimentaciones(Request $request)
    {
        $colmenas = Colmena::with(['alimentaciones', 'apiario'])
            ->where('estado', 'activo')
            ->when($request->apiario, fn($q) => $q->where('idApiario', $request->apiario))
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo')
                  ->where('creadoPor', Auth::id());
            })
            ->get();

        $labels = [];
        $values = [];

        foreach ($colmenas as $colmena) {
            $count = $this->filtrarPorFechas(
                $colmena->alimentaciones(),
                $request->desde,
                $request->hasta,
                'fechaSuministracion'
            )->count();

            $labels[] = $this->etiquetaColmena($colmena);
            $values[] = $count;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }
}
