<?php

namespace App\Http\Controllers;

use App\Models\Colmena;
use App\Models\InspeccionColmena;
use App\Models\Apiario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ControllerColmena extends Controller
{
    public function index()
    {
        // Mostrar SOLO colmenas activas cuyo apiario también está activo
        $colmenas = Colmena::with('apiario')
            ->where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            })
            ->orderBy('idApiario', 'desc')
            ->get();

        return view('colmena.index', compact('colmenas'));
    }

    public function create()
    {
        // Cargar solo apiarios ACTIVOS del usuario
        $idUser = Auth::id();
        $apiarios = Apiario::where('creadoPor', $idUser)
            ->where('estado', 'activo')
            ->withCount('colmenas')
            ->get();

        return view('colmena.create', compact('apiarios'));
    }

    public function store(Request $request)
    {
        // Validar que el apiario existe y está ACTIVO
        $apiario = Apiario::where('idApiario', $request->apiario)
            ->where('estado', 'activo')
            ->first();

        if (!$apiario) {
            return back()->with('error', 'No puedes crear una colmena en un apiario inactivo.');
        }

        $request->validate([
            'codigo' => 'required|string|max:20',
            'fechaInstalacionFisica' => 'nullable|date',
            'estadoOperativo' => 'required|string|in:activa,inactiva,zanganera,huerfana,en_division,enferma',
            'apiario' => 'required|numeric|min:1',
            'cantidadMarco' => 'required|numeric|min:0|max:10',
        ]);

        date_default_timezone_set('America/La_Paz');
        $fecha = date('Y-m-d H:i:s');
        $user = Auth::user()->id;

        $colmena = new Colmena();
        $colmena->codigo = $request->codigo;
        $colmena->fechaInstalacionFisica = $request->fechaInstalacionFisica;
        $colmena->estadoOperativo = $request->estadoOperativo;
        $colmena->idApiario = $request->apiario;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->creadoPor = $user;
        $colmena->modelo = $request->modelo;
        $colmena->fechaCreacion = $fecha;
        $colmena->estado = 'activo';
        $colmena->save();

        return redirect()->to('/colmenas')->with('success', 'Colmena creado exitosamente.');
    }

    public function createLote()
    {
        $idUser = Auth::id();
        $apiarios = Apiario::where('creadoPor', $idUser)
            ->where('estado', 'activo')
            ->withCount('colmenas')
            ->get();

        return view('colmena.createLote', compact('apiarios'));
    }

    public function storeLote(Request $request)
    {
        $validated = $request->validate([
            'colmenas' => 'required|array|min:1',
            'colmenas.*.codigo' => 'required|string|max:20',
            'colmenas.*.apiario' => 'required|numeric|min:1',
            'colmenas.*.fechaInstalacionFisica' => 'nullable|date',
            'colmenas.*.cantidadMarco' => 'required|numeric|min:0|max:10',
            'colmenas.*.modelo' => 'required|string|max:50',
        ]);

        date_default_timezone_set('America/La_Paz');
        $user = Auth::id();
        $fecha = date('Y-m-d H:i:s');

        foreach ($validated['colmenas'] as $col) {

            // Validar que el apiario sigue activo
            $apiario = Apiario::where('idApiario', $col['apiario'])
                ->where('estado', 'activo')
                ->first();

            if (!$apiario) {
                continue; // Evita crear colmenas en apiarios inactivos
            }

            $colmena = new Colmena();
            $colmena->codigo = $col['codigo'];
            $colmena->fechaInstalacionFisica = $col['fechaInstalacionFisica'] ?? null;
            $colmena->estado = 'activo';
            $colmena->idApiario = $col['apiario'];
            $colmena->cantidadMarco = $col['cantidadMarco'];
            $colmena->modelo = $col['modelo'];
            $colmena->creadoPor = $user;
            $colmena->fechaCreacion = $fecha;
            $colmena->save();
        }

        return redirect()->route('colmenas.index')->with('success', 'Colmenas creadas exitosamente.');
    }

    public function show(string $id)
    {
        $colmena = Colmena::with('apiario', 'inspecciones', 'tratamientos')->findOrFail($id);
        return view('colmena.show', compact('colmena'));
    }

    public function edit(string $id)
    {
        $colmena = Colmena::findOrFail($id);
        return view('colmena.edit', compact('colmena'));
    }

    public function update(Request $request, string $id)
    {
        $colmena = Colmena::findOrFail($id);
        $colmena->codigo = $request->codigo;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->modelo = $request->modelo;
        $colmena->estadoOperativo = $request->estadoOperativo;
        $colmena->save();

        return redirect()->to('/colmenas')->with('success', 'Colmena ACTUALIZADO exitosamente.');
    }

    public function destroy(string $id)
    {
        $colmena = Colmena::findOrFail($id);

        // Eliminado lógico
        $colmena->estado = "inactivo";
        $colmena->save();

        return redirect()->to('/colmenas')->with('successdelete', 'Colmena eliminada exitosamente.');
    }

    public function verinspeccion(string $id)
    {
        $colmenas = InspeccionColmena::where('idColmena', $id)->get();
        $cadenaColmena = Colmena::where('idColmena', $id)->first();
        $codigoColmena = $cadenaColmena->codigo;
        $idApiario = $cadenaColmena->idApiario;
        $apiario = Apiario::where('idApiario', $idApiario)->first();
        $nombreApiario = $apiario->nombre;

        return view('colmena.verinspeccion', compact('colmenas', 'id', 'codigoColmena', 'nombreApiario'));
    }

    public function agregarinspeccion(string $id)
    {
        return view('colmena.agregarinspeccion', ['id' => $id]);
    }
}
