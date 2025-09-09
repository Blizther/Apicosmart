<?php

namespace App\Http\Controllers;

use App\Models\Apiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerApiario extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiarios=Apiario::all();
        return view('apiario.index',compact('apiarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apiario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'departamento' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);
        date_default_timezone_set('America/Caracas');
        $fecha=date('Y-m-d H:i:s');
        $user=Auth::user()->id;

        $apiario= new Apiario();
        $apiario->nombre = $request->nombre;
        $apiario->departamento = $request->departamento;
        $apiario->municipio = $request->municipio;
        $apiario->latitud = $request->latitud;
        $apiario->longitud = $request->longitud;
        $apiario->nombre = $request->nombre;
        $apiario->creadoPor = $user;
        $apiario->fechaCreacion = $fecha;
        $apiario->save();

        
        // Redireccionar con mensaje
        return redirect()->to('/apiario')->with('success', 'Apiario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $apiario = Apiario::findOrFail($id);
        return view('apiario.edit', compact('apiario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'departamento' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:8',
        ]);

        // Buscar el producto por ID
        $apiario = Apiario::findOrFail($id);

        //$producto->update($request->all());

        $data = $request->all();

       

        $apiario->update($data);

        return redirect()->to('/apiario')->with('successedit', 'Apiario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $apiario = Apiario::findOrFail($id);
    
        $apiario->delete();
        return redirect()->to('/apiario')->with('successdelete', 'Apiario eliminado exitosamente.');
    }
}
