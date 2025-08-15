<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;


class ControllerVentaUsuario extends Controller
{
    public function metodoVentaUsuario()
    {
        return view ('ventaUsuario.ventaUsuario');
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
