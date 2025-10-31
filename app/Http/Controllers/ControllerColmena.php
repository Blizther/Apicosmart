<?php

namespace App\Http\Controllers;

use App\Models\Colmena;
use App\Models\InspeccionColmena;
use App\Models\Apiario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ControllerColmena extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $colmenas = Colmena::with('apiario')
            ->where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->orderBy('idApiario', 'desc')
            ->get();
        return view('colmena.index', compact('colmenas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cargar los apiarios del usuario autenticado con el conteo de colmenas
        $idUser = Auth::id(); // ID del usuario logueado    
        $apiarios = Apiario::where('creadoPor', $idUser)
            ->where('estado', 'activo')
            ->withCount('colmenas') // Contar colmenas relacionadas
            ->get();

        return view('colmena.create', compact('apiarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'codigo' => 'required|string|max:20',
            'fechaInstalacionFisica' => 'nullable|date',
            'estadoOperativo' => 'required|string|in:activa,inactiva,zanganera,huerfana,en_division,enferma',
            'apiario' => 'required|numeric|min:1',
            'cantidadMarco' => 'required|numeric|min:0|max:10',
            ],
            [
                'codigo.required' => 'El código de la colmena es obligatorio.',
                'codigo.max' => 'El código de la colmena no debe exceder los 20 caracteres.',
                'fechaInstalacionFisica.date' => 'La fecha de instalación física debe ser una fecha válida.',
                'estadoOperativo.required' => 'El estado operativo es obligatorio.',
                'estadoOperativo.in' => 'El estado operativo seleccionado no es válido.',
                'apiario.required' => 'El apiario es obligatorio.',
                'apiario.numeric' => 'El apiario debe ser un valor numérico.',
                'apiario.min' => 'Debe seleccionar un apiario válido.',
                'cantidadMarco.required' => 'La cantidad de marcos es obligatoria.',
                'cantidadMarco.numeric' => 'La cantidad de marcos debe ser un valor numérico.',
                'cantidadMarco.min' => 'La cantidad de marcos no puede ser negativa.',
                'cantidadMarco.max' => 'La cantidad de marcos no puede exceder los 10.',
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
        $colmena->save();

        // Redireccionar con mensaje
        return redirect()->to('/colmenas')->with('success', 'Colmena creado exitosamente.');
    }

    public function createLote()
    {
        $idUser = Auth::id();
        $apiarios = \App\Models\Apiario::where('creadoPor', $idUser)
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
        ],
        [
            'colmenas.required' => 'Debe agregar al menos una colmena.',
            'colmenas.array' => 'Formato de colmenas inválido.',
            'colmenas.*.codigo.required' => 'El código de la colmena es obligatorio.',
            'colmenas.*.codigo.max' => 'El código de la colmena no debe exceder los 20 caracteres.',
            'colmenas.*.apiario.required' => 'El apiario es obligatorio.',
            'colmenas.*.apiario.numeric' => 'El apiario debe ser un valor numérico.',
            'colmenas.*.apiario.min' => 'Debe seleccionar un apiario válido.',
            'colmenas.*.fechaInstalacionFisica.date' => 'La fecha de instalación física debe ser una fecha válida.',
            'colmenas.*.cantidadMarco.required' => 'La cantidad de marcos es obligatoria.',
            'colmenas.*.cantidadMarco.numeric' => 'La cantidad de marcos debe ser un valor numérico.',
            'colmenas.*.cantidadMarco.min' => 'La cantidad de marcos no puede ser negativa.',
            'colmenas.*.cantidadMarco.max' => 'La cantidad de marcos no puede exceder los 10.',
            'colmenas.*.modelo.required' => 'El modelo de la colmena es obligatorio.',
            'colmenas.*.modelo.max' => 'El modelo de la colmena no debe exceder los 50 caracteres.',
        ]);

        date_default_timezone_set('America/Caracas');
        $user = Auth::id();
        $fecha = date('Y-m-d H:i:s');

        foreach ($validated['colmenas'] as $col) {
            $colmena = new \App\Models\Colmena();
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
    public function proximoCodigo($idApiario)
    {
        $ultima = \App\Models\Colmena::where('idApiario', $idApiario)
            ->orderByDesc('idColmena')
            ->first();

        // Tomamos el código base
        $nuevoCodigo = 'C-001';

        if ($ultima && preg_match('/(\d+)$/', $ultima->codigo, $coincidencias)) {
            $numero = (int)$coincidencias[1] + 1;
            $nuevoCodigo = preg_replace('/\d+$/', str_pad($numero, 3, '0', STR_PAD_LEFT), $ultima->codigo);
        } elseif ($ultima && !preg_match('/(\d+)$/', $ultima->codigo)) {
            // Si el código no tenía número, agregamos el primero
            $nuevoCodigo = $ultima->codigo . '-001';
        }

        return response()->json(['codigo' => $nuevoCodigo]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //se debe mostrar la informacion de una colmena, incluyendo su apiario, la lista de las inspecciones realizadas y los tratamientos
        $colmena = Colmena::with('apiario', 'inspecciones', 'tratamientos')->findOrFail($id);
        return view('colmena.show', compact('colmena'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $colmena = Colmena::findOrFail($id);
        return view('colmena.edit', compact('colmena'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $colmena = Colmena::findOrFail($id);
        $colmena->codigo = $request->codigo;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->modelo = $request->modelo;
        $colmena->estadoOperativo = $request->estadoOperativo;
        $colmena->save();
        return redirect()->to('/colmenas')->with('success', 'Colmena ACTUALIZADO exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $colmenas = Colmena::findOrFail($id);
        $colmenas->estado = "inactivo";

        $colmenas->save();
        return redirect()->to('/colmenas')->with('successdelete', 'Colmena eliminada exitosamente.');
    }
    public function verinspeccion(string $id)
    {

        $colmenas = InspeccionColmena::where('idColmena', $id)->get();
        return view('colmena.verinspeccion', compact('colmenas', 'id'));
    }
    public function agregarinspeccion(string $id)
    {


        return view('colmena.agregarinspeccion', ['id' => $id]);
    }
}
