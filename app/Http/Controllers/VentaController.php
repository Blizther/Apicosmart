<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use App\Models\Detalle;
use App\Models\Producto;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function cartAdd(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad'    => 'required|integer|min:1',
        ],
        [
            'producto_id.required' => 'El producto es obligatorio.',
            'producto_id.integer' => 'El producto debe ser un valor numérico.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un valor numérico.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        $userId = Auth::id();

        $producto = Producto::where('id', $data['producto_id'])
            ->where('idUser', $userId)
            ->firstOrFail();

        if ($producto->stock < $data['cantidad']) {
            return back()->withErrors(['stock' => 'Stock insuficiente para este producto.']);
        }

        $cart = session('cart', []);
        if (isset($cart[$producto->id])) {
            $nueva = $cart[$producto->id]['cantidad'] + $data['cantidad'];
            if ($nueva > $producto->stock) {
                return back()->withErrors(['stock' => 'La cantidad supera el stock disponible.']);
            }
            $cart[$producto->id]['cantidad'] = $nueva;
        } else {
            $cart[$producto->id] = [
                'id'          => $producto->id,
                'descripcion' => $producto->descripcion,
                'precio'      => (float)$producto->precio,
                'cantidad'    => (int)$data['cantidad'],
            ];
        }

        session(['cart' => $cart]);
        return redirect('/ventaUsuario')->with('ok', 'Producto agregado al carrito.');
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $producto = Producto::where('id', $data['producto_id'])
            ->where('idUser', $userId)
            ->firstOrFail();

        $cart = session('cart', []);
        if (!isset($cart[$producto->id])) {
            return back()->withErrors(['carrito' => 'El producto no está en el carrito.']);
        }

        if ($data['cantidad'] > $producto->stock) {
            return back()->withErrors(['stock' => 'La cantidad supera el stock disponible.']);
        }

        $cart[$producto->id]['cantidad'] = (int)$data['cantidad'];
        session(['cart' => $cart]);

        return redirect('/ventaUsuario')->with('ok', 'Carrito actualizado.');
    }

    public function cartRemove(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer',
        ]);

        $userId = Auth::id();
        $producto = Producto::where('id', $data['producto_id'])
            ->where('idUser', $userId)
            ->firstOrFail();

        $cart = session('cart', []);
        unset($cart[$producto->id]);
        session(['cart' => $cart]);

        return redirect('/ventaUsuario')->with('ok', 'Producto quitado del carrito.');
    }

    /**
     * Checkout
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'cliente_nombre' => 'nullable|string|max:150',
            'metodo_pago'    => 'nullable|string|max:50',
        ]);

        // Cliente limpio
        $cliente = trim($request->input('cliente_nombre', ''));
        if ($cliente === '') {
            $cliente = 'SIN NOMBRE';
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        $productoIds = array_keys($cart);
        $productos = Producto::whereIn('id', $productoIds)
            ->where('idUser', $userId)
            ->lockForUpdate()
            ->get(['id','precio','stock']);

        if ($productos->count() !== count($productoIds)) {
            return back()->withErrors(['propiedad' => 'Se detectaron ítems que no pertenecen al usuario.']);
        }

        $total = 0;
        foreach ($productos as $p) {
            $cant = $cart[$p->id]['cantidad'];
            if ($p->stock < $cant) {
                return back()->withErrors(['stock' => "Stock insuficiente para el producto ID {$p->id}."]);
            }
            $total += $p->precio * $cant;
        }

        DB::beginTransaction();
        try {

            /**
             * ✅ IMPORTANTE:
             * No usamos Venta::create para el cliente.
             * Guardamos explícitamente para evitar que quede NULL.
             */
            $venta = new Venta();
            $venta->idUser = $userId;
            $venta->fecha  = Carbon::now();
            $venta->total  = $total;
            $venta->estado = 1;
            $venta->save();

            // Forzamos guardado del cliente (doble seguro)
            $venta->cliente_nombre = $cliente;
            $venta->save();

            // Extra seguro: update directo a BD (si algo raro lo pisa)
            Venta::where('id', $venta->id)->update([
                'cliente_nombre' => $cliente
            ]);

            foreach ($productos as $p) {
                $cant = $cart[$p->id]['cantidad'];
                $precioUnit = $p->precio;
                $sub = $precioUnit * $cant;

                Detalle::create([
                    'idVenta'         => $venta->id,
                    'idProducto'      => $p->id,
                    'cantidad'        => $cant,
                    'precio_unitario' => $precioUnit,
                    'subtotal'        => $sub,
                ]);

                $p->decrement('stock', $cant);
            }

            DB::commit();
            session()->forget('cart');

            // ✅ Como quieres: solo mensaje y vuelve a la venta
            return redirect('/ventaUsuario')
                ->with('ok', "Venta #{$venta->id} registrada correctamente.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se pudo completar la venta: '.$e->getMessage()]);
        }
    }
}
