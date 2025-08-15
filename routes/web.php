<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControllerProducto;
use App\Http\Controllers\ControllerVentaUsuario;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// creando rutas protegidas por rol
// Rutas para administrador
Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::get('/administrador/inicio', function () {
        return view('administrador.inicio');
    });
    Route::resource('users', UserController::class);
});

// Rutas para usuario común
Route::middleware(['auth', 'rol:usuario'])->group(function () {
    Route::get('/usuario/inicio', function () {
        return view('usuario.inicio');
    });
    Route::get('/productos', [ControllerProducto::class, 'metodoProductos']);
    // Mostrar formulario de creación
    Route::get('/productos/crearproducto', [ControllerProducto::class, 'nuevoproducto']);
    // Guardar nuevo producto - es post no get
    Route::post('/productos/guardarproducto', [ControllerProducto::class, 'nuevoproductobd'])->middleware('auth');
    //Ruta para eliminado (enviando id por direccion url)
    Route::delete('/productos/eliminarproducto/{id}', [ControllerProducto::class, 'eliminarproductobd'])->name('productos.destroy');
    // Mostrar formulario de edición (es get por que muestra formulario de edición)
    Route::get('/productos/{id}/editarproducto', [ControllerProducto::class, 'editarProducto'])->name('productos.editar');
    // Actualizar producto (se utiliza el metodo put)
    Route::put('/productos/editarproducto/{id}', [ControllerProducto::class, 'actualizarProducto'])->name('productos.actualizar');




    Route::get('/ventaUsuario', [ControllerVentaUsuario::class, 'metodoVentaUsuario']);
    Route::get('/reporteUsuario', [ControllerVentaUsuario::class, 'metodoReporteUsuario']);
    Route::get('/stockUsuario', [ControllerVentaUsuario::class, 'metodoStockUsuario']);
});
