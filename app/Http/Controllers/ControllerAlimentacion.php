<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimentacion;
use App\Models\Colmena;
use Illuminate\Support\Facades\Auth;

class ControllerAlimentacion extends Controller
{
    //lista de alimentaciones de colmenas con estado activo del usuario autenticado
    public function index()
    {
        
        $alimentaciones = Alimentacion::with('colmena')
                        ->where('idUsuario', Auth::id())
                        ->orderBy('fechaSuministracion', 'desc')
                        ->get();
        return view('alimentacion.index', compact('alimentaciones'));
    }
    // Show the form for creating a new resource.
    public function create()
    {
        // Cargar las colmenas del usuario autenticado y que tengan estado activo
        $idUser = Auth::id(); // ID del usuario logueado   
        $colmenas = Colmena::where('creadoPor', $idUser)
                    ->where('estado', 'activo')
                    ->get();   
        return view('alimentacion.create',compact('colmenas'));
    }
    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'tipoAlimento' => 'required|string|max:100',
            'cantidad' => 'required|numeric|min:0',
            'unidadMedida' => 'required|string|max:20',
            'motivo' => 'required|string|max:255',
            'fechaSuministracion' => 'required|date',
            'idColmena' => 'required|numeric|min:1',
            'obervaciones' => 'nullable|string',
        ]);
        date_default_timezone_set('America/Caracas');
        $fecha=date('Y-m-d H:i:s');
        $user=Auth::user()->id; 
        $alimentacion= new Alimentacion();
        $alimentacion->tipoAlimento = $request->tipoAlimento;
        $alimentacion->cantidad = $request->cantidad;
        $alimentacion->unidadMedida = $request->unidadMedida;
        $alimentacion->motivo = $request->motivo;
        $alimentacion->fechaSuministracion = $request->fechaSuministracion;
        $alimentacion->observaciones = $request->observaciones;
        $alimentacion->idUsuario = $user;
        $alimentacion->idColmena = $request->idColmena;
        $alimentacion->save();
         return redirect()->to('/alimentacion')->with('success', 'Alimentación registrada exitosamente.');
    }
    
}
