<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerVentaUsuario extends Controller
{
    public function metodoVentaUsuario()
    {
        $userId = Auth::id();

        // Solo productos del dueño logueado
        $productos = Producto::where('idUser', $userId)
            ->orderBy('descripcion')
            ->get(['id','descripcion','precio','stock','imagen','unidadMedida','estado']);

        // Carrito en sesión (propio del usuario)
        $cart = session('cart', []); // [producto_id => ['id','descripcion','precio','cantidad']]
        $total = array_reduce($cart, fn($c,$i)=> $c + ($i['precio'] * $i['cantidad']), 0);

        return view('ventaUsuario.ventaUsuario', compact('productos','cart','total'));
    }

    public function metodoStockUsuario()
    {
        return view ('ventaUsuario.stockUsuario');
    }
    public function metodoReporteUsuario()
    {
        return view ('ventaUsuario.reporteUsuario');
    }

}
