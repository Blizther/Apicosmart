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
        // Mostrar SOLO colmenas activas y con apiarios activos
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
        $idUser = Auth::id();
        $apiarios = Apiario::where('creadoPor', $idUser)
            ->where('estado', 'activo')
            ->withCount('colmenas')
            ->get();

        return view('colmena.create', compact('apiarios'));
    }

    public function store(Request $request)
    {
        // Validar APIARIO activo
        $apiario = Apiario::where('idApiario', $request->apiario)
            ->where('estado', 'activo')
            ->first();

        if (!$apiario) {
            return back()->with('error', 'No puedes crear una colmena en un apiario inactivo.');
        }

        // VALIDACIÓN EN ESPAÑOL
        $request->validate([
            'codigo' => 'required|string|max:20',
            'fechaInstalacionFisica' => 'nullable|date',
            'estadoOperativo' => 'required|in:activa,inactiva,zanganera,huerfana,en_division,enferma',
            'apiario' => 'required|numeric|min:1',
            'cantidadMarco' => 'required|numeric|min:1|max:12',
            'modelo' => 'nullable|string|max:50',
        ], [
            'codigo.required' => 'El código de la colmena es obligatorio.',
            'codigo.max' => 'El código no debe exceder 20 caracteres.',

            'estadoOperativo.required' => 'Debe seleccionar un estado operativo.',
            'estadoOperativo.in' => 'El estado operativo seleccionado no es válido.',

            'apiario.required' => 'Debe seleccionar un apiario.',
            'apiario.numeric' => 'El apiario seleccionado no es válido.',

            'cantidadMarco.required' => 'Debe ingresar la cantidad de marcos.',
            'cantidadMarco.numeric' => 'La cantidad de marcos debe ser un número.',
            'cantidadMarco.min' => 'La colmena debe tener al menos 1 marco.',
            'cantidadMarco.max' => 'La colmena puede tener como máximo 12 marcos.',

            'modelo.max' => 'El modelo no debe exceder 50 caracteres.',
        ]);

        date_default_timezone_set('America/La_Paz');

        $colmena = new Colmena();
        $colmena->codigo = $request->codigo;
        $colmena->fechaInstalacionFisica = $request->fechaInstalacionFisica;
        $colmena->estadoOperativo = $request->estadoOperativo;
        $colmena->idApiario = $request->apiario;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->modelo = $request->modelo;
        $colmena->creadoPor = Auth::id();
        $colmena->fechaCreacion = date('Y-m-d H:i:s');
        $colmena->estado = 'activo';
        $colmena->save();

        return redirect()->to('/colmenas')->with('success', 'Colmena creada exitosamente.');
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
            'colmenas.*.cantidadMarco' => 'required|numeric|min:1|max:12',
            'colmenas.*.modelo' => 'required|string|max:50',

        ], [
            'colmenas.required' => 'Debe registrar al menos una colmena.',
            'colmenas.array' => 'El formato enviado no es válido.',

            'colmenas.*.codigo.required' => 'Cada colmena debe tener un código.',
            'colmenas.*.codigo.max' => 'El código no debe exceder 20 caracteres.',

            'colmenas.*.apiario.required' => 'Debe seleccionar un apiario.',
            'colmenas.*.apiario.numeric' => 'El apiario seleccionado no es válido.',

            'colmenas.*.cantidadMarco.required' => 'Debe ingresar la cantidad de marcos.',
            'colmenas.*.cantidadMarco.numeric' => 'La cantidad de marcos debe ser numérica.',
            'colmenas.*.cantidadMarco.min' => 'Cada colmena debe tener al menos 1 marco.',
            'colmenas.*.cantidadMarco.max' => 'Cada colmena puede tener como máximo 12 marcos.',

            'colmenas.*.modelo.required' => 'Debe ingresar el modelo de la colmena.',
            'colmenas.*.modelo.max' => 'El modelo no debe exceder 50 caracteres.',
        ]);

        date_default_timezone_set('America/La_Paz');

        foreach ($validated['colmenas'] as $col) {

            $apiario = Apiario::where('idApiario', $col['apiario'])
                ->where('estado', 'activo')
                ->first();

            if (!$apiario) continue;

            Colmena::create([
                'codigo' => $col['codigo'],
                'fechaInstalacionFisica' => $col['fechaInstalacionFisica'] ?? null,
                'estado' => 'activo',
                'idApiario' => $col['apiario'],
                'cantidadMarco' => $col['cantidadMarco'],
                'modelo' => $col['modelo'],
                'creadoPor' => Auth::id(),
                'fechaCreacion' => date('Y-m-d H:i:s'),
            ]);
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
        $request->validate([
            'codigo' => 'required|string|max:20',
            'cantidadMarco' => 'required|numeric|min:1|max:12',
            'modelo' => 'nullable|string|max:50',
            'estadoOperativo' => 'required|in:activa,inactiva,zanganera,huerfana,en_division,enferma',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.max' => 'El código no debe exceder 20 caracteres.',

            'cantidadMarco.required' => 'Debe ingresar la cantidad de marcos.',
            'cantidadMarco.numeric' => 'La cantidad debe ser un número.',
            'cantidadMarco.min' => 'Debe tener mínimo 1 marco.',
            'cantidadMarco.max' => 'Máximo permitido: 12 marcos.',

            'modelo.max' => 'El modelo no debe exceder 50 caracteres.',

            'estadoOperativo.required' => 'Debe seleccionar un estado operativo.',
            'estadoOperativo.in' => 'El estado operativo seleccionado no es válido.',
        ]);

        $colmena = Colmena::findOrFail($id);

        $colmena->codigo = $request->codigo;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->modelo = $request->modelo;
        $colmena->estadoOperativo = $request->estadoOperativo;
        $colmena->save();

        return redirect()->to('/colmenas')
            ->with('success', 'Colmena actualizada exitosamente.');
    }

    public function destroy(string $id)
    {
        $colmena = Colmena::findOrFail($id);
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
