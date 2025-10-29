<?php

namespace App\Http\Controllers;
use App\Models\Cosecha;
use App\Models\Colmena;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ControllerCosecha extends Controller
{
   // mostrar lista de cosechas
    public function index()
    {
        $cosechas = Cosecha::with('colmena')
            ->whereHas('colmena', function ($query) {
                $query->where('estado', 'activo')
                    ->where('creadoPor', Auth::id()); // dueño de la colmena
            })
            ->where('estado', 'activo') // <-- mostrar solo cosechas activas
            ->orderBy('fechaCosecha', 'desc')
            ->get();

        return view('cosecha.index', compact('cosechas'));
    }


    //mostrar formulario de nueva cosecha
    public function create()
    {
        //colmenas activas del usuario autenticado
        $colmenas = Colmena::where('creadoPor', Auth::id())
                    ->where('estado', 'activo') 

                    ->get();
        return view('cosecha.create', compact('colmenas')); 
    }
    //guardar nueva cosecha
    public function store(Request $request)
    {
        $request->validate([
            'peso' => 'required|numeric|min:0',
            'estadoMiel' => 'required|string|max:20',
            'fechaCosecha' => 'required|date',
            'idColmena' => 'required|numeric|min:1',
            'observaciones' => 'nullable|string|max:255',
        ]);     
        date_default_timezone_set('America/Caracas');
        $fecha=date('Y-m-d H:i:s');
        $user=Auth::user()->id;

        $cosecha= new Cosecha();
        $cosecha->peso = $request->peso;
        $cosecha->estadoMiel = $request->estadoMiel;
        $cosecha->fechaCosecha = $request->fechaCosecha;
        $cosecha->idColmena = $request->idColmena;
        $cosecha->observaciones = $request->observaciones;
        $cosecha->estado = 'activo';
        $cosecha->save();       
        return redirect()->route('cosechas.index')->with('success', 'Cosecha registrada exitosamente.');

    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //  
    }
    public function destroy($id)
{
    // Buscar la cosecha por su id
    $cosecha = Cosecha::findOrFail($id);

    // Marcarla como inactiva (eliminado lógico)
    $cosecha->estado = "inactivo";
    $cosecha->save();

    return redirect()
        ->route('cosechas.index')
        ->with('successdelete', 'Cosecha eliminada exitosamente.');
}



}
