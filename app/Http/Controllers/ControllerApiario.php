<?php

namespace App\Http\Controllers;

use App\Models\Apiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ControllerApiario extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$apiarios=Apiario::all();
        
        $idUser = Auth::id(); // ID del usuario logueado
        $apiarios = Apiario::where('creadoPor', $idUser)
                    ->where('estado', 'activo')
                    ->get();
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
         $userId = Auth::id();
        $request->validate([
        'nombre' => [
            'required',
            'max:150',
            Rule::unique('apiario')->where(function ($query) use ($userId) {
                return $query->where('creadoPor', $userId)
                             ->where('estado', 'activo');
            }),
        ],
        'vegetacion' => 'string|max:100',
        'urlImagen' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        'altitud' => 'numeric',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
    ], [
        'nombre.required' => 'El nombre del apiario es obligatorio.',
        'nombre.max' => 'El nombre del apiario no debe exceder los 150 caracteres.',
        'nombre.unique' => 'Ya tienes un apiario con este nombre. Por favor elige otro.',
        'vegetacion.string' => 'La vegetación debe ser una cadena de texto.',
        'vegetacion.max' => 'La vegetación no debe exceder los 100 caracteres.',
        'urlImagen.image' => 'El archivo debe ser una imagen.',
        'urlImagen.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg.',
        'urlImagen.max' => 'La imagen no debe exceder los 3 MB.',
        'altitud.numeric' => 'La altitud debe ser un valor numérico.',
        'latitud.required' => 'La latitud es obligatoria.',
        'latitud.numeric' => 'La latitud debe ser un valor numérico.',
        'longitud.required' => 'La longitud es obligatoria.',
        'longitud.numeric' => 'La longitud debe ser un valor numérico.',
    ]);
        //personalizar mensajes de error en la validación



        date_default_timezone_set('America/La_Paz');
        $fecha=date('Y-m-d H:i:s');
        $user=Auth::user()->id;

        

        $apiario= new Apiario();
        $apiario->nombre = $request->nombre;
        $apiario->vegetacion = $request->vegetacion;
        $apiario->altitud = $request->altitud;
        $apiario->latitud = $request->latitud;
        $apiario->longitud = $request->longitud;
        
        $apiario->creadoPor = $user;
        $apiario->fechaCreacion = $fecha;

        if ($request->hasFile('urlImagen')) {
            $file = $request->file('urlImagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nombreArchivo);
            $apiario->urlImagen = 'uploads/' . $nombreArchivo;
            
        }
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
            'vegetacion' => 'required|string|max:100',
            'urlImagen' => 'string|max:100',
            'altitud' => 'numeric',
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
    
        $apiario->estado = "inactivo";
        $apiario->save();
        return redirect()->to('/apiario')->with('successdelete', 'Apiario eliminado exitosamente.');
    }
    public function vercolmenas(string $id)
    {   
        
        $apiario = Apiario::findOrFail($id);
        //obtener colmenas asociadas al apiario con estado activo
        $colmenas = $apiario->colmenas()->where('estado', 'activo')->get();
        return view('apiario.verapiario', compact('apiario', 'colmenas'));
    }
    
}
