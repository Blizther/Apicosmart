<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ControllerProducto extends Controller
{
    /*public function metodoProductos()
    {
        $productos=Producto::all(); //{{ auth()->user()->name }}
        return view ('productos.productos',compact('productos'));
    }*/
    public function metodoProductos()
    {
        $idUser = Auth::id(); // ID del usuario logueado
        $productos = Producto::where('idUser', $idUser)->get();

        //$productos = Producto::all(); MODIFICADO
        return view('productos.productos', compact('productos'));
    }

    public function nuevoproducto()
    {
        return view('productos.formnuevoproducto');
    }

    public function nuevoproductobd(Request $request)
    {
        // Validación con solo letras y sin productos duplicados por usuario
        $request->validate([
            'descripcion' => [
                'required',
                'max:150',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/u',
                Rule::unique('productos', 'descripcion')->where(function ($query) {
                    return $query->where('idUser', Auth::id());
                }),
            ],
            'unidadMedida' => 'required|max:25',
            'stock'        => 'required|integer|min:0',
            'precio'       => 'required|numeric|min:0',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ], [
            'descripcion.required' => 'El nombre del producto es obligatorio.',
            'descripcion.max'      => 'El nombre del producto no debe ser mayor de 150 caracteres.',
            'descripcion.regex'    => 'El nombre del producto solo puede contener letras y espacios.',
            'descripcion.unique'   => 'Ya existe un producto con ese nombre.',
            'unidadMedida.max'     => 'La Unidad de Medida no debe ser mayor de 25 caracteres.',
            'unidadMedida.required'=> 'La Unidad de Medida es obligatoria.',
            'stock.required'       => 'El campo stock es obligatorio.',
            'stock.integer'        => 'El campo stock debe ser un número entero.',
            'stock.min'            => 'El campo stock debe ser al menos 0.',
            'precio.required'      => 'El campo precio es obligatorio.',
            'precio.numeric'       => 'El campo precio debe ser un número.',
            'precio.min'           => 'El campo precio debe ser al menos 0.',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nombreArchivo);
            $data['imagen'] = 'uploads/' . $nombreArchivo;
        }

        $data['idUser'] = Auth::id();
        Producto::create($data);

        // Redireccionar con mensaje
        return redirect()->to('/productos')->with('success', 'Producto creado exitosamente.');
    }


    public function eliminarproductobd($id)
    {
        $producto = Producto::findOrFail($id);

        // Eliminar la imagen del servidor si existe
        if ($producto->imagen && file_exists(public_path($producto->imagen))) {
            unlink(public_path($producto->imagen));
        }
    
        $producto->delete();
        return redirect()->to('/productos')->with('successdelete', 'Producto eliminado exitosamente.');
    }



    public function editarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.editarproducto', compact('producto'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'descripcion' => [
                'required',
                'max:150',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/u',
                Rule::unique('productos', 'descripcion')
                    ->where(function ($query) {
                        return $query->where('idUser', Auth::id());
                    })
                    ->ignore($producto->id),
            ],
            'unidadMedida' => 'required|max:25',
            'stock'        => 'required|integer|min:0',
            'precio'       => 'required|numeric|min:0',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ], [
            'descripcion.required' => 'El nombre del producto es obligatorio.',
            'descripcion.max'      => 'El nombre del producto no debe ser mayor de 150 caracteres.',
            'descripcion.regex'    => 'El nombre del producto solo puede contener letras y espacios.',
            'descripcion.unique'   => 'Ya existe un producto con ese nombre.',
            'unidadMedida.max'     => 'La Unidad de Medida no debe ser mayor de 25 caracteres.',
            'unidadMedida.required'=> 'La Unidad de Medida es obligatoria.',
            'stock.required'       => 'El campo stock es obligatorio.',
            'stock.integer'        => 'El campo stock debe ser un número entero.',
            'stock.min'            => 'El campo stock debe ser al menos 0.',
            'precio.required'      => 'El campo precio es obligatorio.',
            'precio.numeric'       => 'El campo precio debe ser un número.',
            'precio.min'           => 'El campo precio debe ser al menos 0.',
        ]);
        
        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }

            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $nombreArchivo);
            $data['imagen'] = 'uploads/' . $nombreArchivo;
        }

        $producto->update($data);

        return redirect()->to('/productos')->with('successedit', 'Producto actualizado correctamente.');
    }
}
