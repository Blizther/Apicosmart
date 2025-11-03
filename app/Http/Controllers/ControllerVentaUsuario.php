<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
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
            ->get(['id', 'descripcion', 'precio', 'stock', 'imagen', 'unidadMedida', 'estado']);

        // Carrito en sesión (propio del usuario)
        $cart = session('cart', []); // [producto_id => ['id','descripcion','precio','cantidad']]
        $total = array_reduce($cart, fn($c, $i) => $c + ($i['precio'] * $i['cantidad']), 0);

        return view('ventaUsuario.ventaUsuario', compact('productos', 'cart', 'total'));
    }

    public function metodoStockUsuario()
    {
        return view('ventaUsuario.stockUsuario');
    }

    public function metodoReporteUsuario(Request $request)
    {
        $uid  = Auth::id();

        // Filtros (por defecto últimos 30 días)
        $from = $request->input('from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $to   = $request->input('to',   Carbon::now()->format('Y-m-d'));

        // Normaliza rango [from 00:00:00, to 23:59:59]
        $fromStart = Carbon::parse($from)->startOfDay();
        $toEnd     = Carbon::parse($to)->endOfDay();

        // Trae ventas del usuario, con detalles para calcular items
        $ventas = Venta::with(['detalles']) // relación detalles: hasMany(Detalle::class,'idVenta')
            ->where('idUser', $uid)
            ->whereBetween('fecha', [$fromStart, $toEnd])
            ->orderBy('fecha', 'desc')
            ->get();

        // Resumen
        $cantidadVentas = $ventas->count();
        $totalVendido   = $ventas->sum('total');
        $itemsVendidos  = $ventas->flatMap->detalles->sum('cantidad'); // suma de cantidades en todos los detalles

        $resumen = [
            'cantidadVentas' => $cantidadVentas,
            'totalVendido'   => $totalVendido,
            'itemsVendidos'  => $itemsVendidos,
        ];

        return view('ventaUsuario.reporteUsuario', compact('ventas', 'from', 'to', 'resumen'));
    }
    
    public function mostrarVenta($ventaId)
    {
        $uid = Auth::id();

        // Solo permite ver ventas del dueño logueado
        $venta = Venta::with(['usuario', 'detalles.producto'])
            ->where('idUser', $uid)
            ->findOrFail($ventaId);

        return view('ventaUsuario.reporteUsuario_detalle', compact('venta'));
    }
}
