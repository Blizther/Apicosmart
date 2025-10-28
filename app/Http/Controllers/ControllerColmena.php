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
                   ->orderBy('idApiario','desc')
                   ->get();
        return view('colmena.index',compact('colmenas'));
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

        return view('colmena.create',compact('apiarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'codigo' => 'required|string|max:20',
            'fechaFabricacion' => 'nullable|date',
            'estado' => 'required|string|max:8',
            'apiario' => 'required|numeric|min:1',
            'cantidadMarco' => 'required|numeric|min:0|max:10',
        ]);
        date_default_timezone_set('America/Caracas');
        $fecha=date('Y-m-d H:i:s');
        $user=Auth::user()->id;

        $colmena= new Colmena();
        $colmena->codigo = $request->codigo;
        $colmena->fechaInstalacionFisica = $request->fechaFabricacion;
        $colmena->estado = $request->estado;
        $colmena->idApiario = $request->apiario;
        $colmena->cantidadMarco = $request->cantidadMarco;
        $colmena->creadoPor = $user;
        $colmena->modelo = $request->modelo;
        $colmena->fechaCreacion = $fecha;
        $colmena->save();
            
        // Redireccionar con mensaje
        return redirect()->to('/colmenas')->with('success', 'Colmena creado exitosamente.');
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
        $colmena=Colmena::findOrFail($id);
        return view('colmena.edit',compact('colmena'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $colmena=Colmena::findOrFail($id);
        $colmena->codigo=$request->codigo;
        $colmena->cantidadMarco=$request->cantidadMarco;
        $colmena->modelo=$request->modelo;
        $colmena->estadoOperativo=$request->estadoOperativo;
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
    public function verinspeccion(string $id){

        $colmenas=InspeccionColmena::where('idColmena',$id)->get();
        return view('colmena.verinspeccion',compact('colmenas','id'));
    }
     public function agregarinspeccion(string $id){

        
        return view('colmena.agregarinspeccion',['id'=>$id]);
    }
}
