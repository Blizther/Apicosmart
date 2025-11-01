<?php

use App\Http\Controllers\DispositivoFabricadoController;
use App\Http\Controllers\DispositivoWebController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControllerApiario;
use App\Http\Controllers\ControllerColmena;
use App\Http\Controllers\ControllerProducto;
use App\Http\Controllers\ControllerVentaUsuario;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ControllerInspeccionColmena;
use App\Http\Controllers\ControllerTratamiento;
use App\Http\Controllers\ControllerAlimentacion;
use App\Http\Controllers\ControllerEstadisticas;
use App\Http\Controllers\ControllerCosecha;
use App\Http\Controllers\ControllerEstadisticasColmenas;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/', function () {
//    return view('auth.login');
//});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// creando rutas protegidas por rol
// Rutas para administrador
Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::get('/administrador/inicio', function () {
        return view('administrador.dashboard');
    });
    Route::resource('users', UserController::class);
    Route::get('/administrador/dispositivos-fabricados',       [DispositivoFabricadoController::class, 'index'])->name('fabricados.index');
    Route::get('/administrador/dispositivos-fabricados/crear', [DispositivoFabricadoController::class, 'create'])->name('fabricados.create');
    Route::post('/administrador/dispositivos-fabricados',      [DispositivoFabricadoController::class, 'store'])->name('fabricados.store');
});

// Rutas para usuario apicultor
Route::middleware(['auth', 'rol:usuario'])->group(function () {
    Route::get('/usuario/inicio', function () {
        return view('usuario.dashboard');
    })->name('usuario.inicio');
    Route::get('/productos', [ControllerProducto::class, 'metodoProductos']);
    // Mostrar formulario de creación
    Route::get('/productos/crearproducto', [ControllerProducto::class, 'nuevoproducto']);
    // Guardar nuevo producto - es post no get
    Route::post('/productos/guardarproducto', [ControllerProducto::class, 'nuevoproductobd'])->middleware('auth');
    //Route::post('/productos/guardarproducto', [ControllerProducto::class, 'nuevoproductobd']); MODIFICADO
    //Ruta para eliminado (enviando id por direccion url)
    Route::delete('/productos/eliminarproducto/{id}', [ControllerProducto::class, 'eliminarproductobd'])->name('productos.destroy');
    // Mostrar formulario de edición (es get por que muestra formulario de edición)
    Route::get('/productos/{id}/editarproducto', [ControllerProducto::class, 'editarProducto'])->name('productos.editar');
    // Actualizar producto (se utiliza el metodo put)
    Route::put('/productos/editarproducto/{id}', [ControllerProducto::class, 'actualizarProducto'])->name('productos.actualizar');

    //SECCION APIARIO
    Route::get('/apiario', [ControllerApiario::class, 'index'])->name('apiario.index');
    // Mostrar formulario de creación
    Route::get('/apiario/crearapiario', [ControllerApiario::class, 'create'])->name('apiario.create');
    // Guardar nuevo producto - es post no get
    Route::post('/apiario/guardarapiario', [ControllerApiario::class, 'store'])->name('apiario.store');
    //Ruta para eliminado (enviando id por direccion url)
    Route::delete('/apiario/eliminarapiario/{id}', [ControllerApiario::class, 'destroy'])->name('apiario.destroy');
    // Mostrar formulario de edición (es get por que muestra formulario de edición)
    Route::get('/apiario/{id}/editarapiario', [ControllerApiario::class, 'edit'])->name('apiario.edit');
    // Actualizar producto (se utiliza el metodo put)
    Route::put('/apiario/editarapiario/{id}', [ControllerApiario::class, 'update'])->name('apiario.update');
    Route::get('/apiario/{id}/verapiario', [ControllerApiario::class, 'vercolmenas'])->name('apiario.verapiario');

    /*********SECCION COLMENA******/
    // Crear varias colmenas a la vez
    Route::get('/colmenas/crear-lote', [ControllerColmena::class, 'createLote'])->name('colmenas.createLote');
    Route::post('/colmenas/guardar-lote', [ControllerColmena::class, 'storeLote'])->name('colmenas.storeLote');
    // Obtener el siguiente código de colmena según el apiario
    Route::get('/colmenas/proximo-codigo/{idApiario}', [ControllerColmena::class, 'proximoCodigo'])->name('colmenas.proximoCodigo');

    Route::resource('/colmenas', ControllerColmena::class);
    Route::get('/colmenas/{id}/verInspeccion', [ControllerColmena::class, 'verinspeccion'])->name('colmenas.verinspeccion');
    Route::get('/colmenas/{id}/agregarinspeccion', [ControllerColmena::class, 'agregarinspeccion'])->name('colmenas.agregarinspeccion');
    Route::post('/colmenas/guardarinspeccion', [ControllerInspeccionColmena::class, 'store'])->name('inspeccion.store');
    Route::get('/colmenas/{id}/editar', [ControllerColmena::class, 'edit'])->name('colmenas.edit');
    Route::get('/colmenas/{id}/guardarcolmena', [ControllerColmena::class, 'update'])->name('colmenas.update');
    Route::put('/colmenas/editarcolmena/{id}', [ControllerColmena::class, 'update'])->name('colmenas.update');
    //detalles de la colmena
    Route::get('/colmenas/{id}', [ControllerColmena::class, 'show'])->name('colmenas.show');

    //SECCION COSECHA
    Route::resource('/cosechas', ControllerCosecha::class);
    Route::get('/cosechas/crearcosecha', [ControllerCosecha::class, 'create'])->name('cosecha.create');
    Route::post('/cosechas/guardarcosecha', [ControllerCosecha::class, 'store'])->name('cosecha.store');
    Route::get('/cosechas/{id}/', [ControllerCosecha::class, 'index'])->name('cosecha.index');

    /********SECCION TRATAMIENTO********/
    Route::resource('/tratamiento', 'App\Http\Controllers\ControllerTratamiento');
    Route::get('/tratamiento', [ControllerTratamiento::class, 'index'])->name('tratamiento.index');
    //SECCION ALIMENTACION
    Route::resource('/alimentacion', 'App\Http\Controllers\ControllerAlimentacion');
    Route::get('/alimentacion', [ControllerAlimentacion::class, 'index'])->name('alimentacion.index');

    Route::prefix('estadisticas')->group(function () {
        Route::get('/', [ControllerEstadisticas::class, 'index'])->name('estadisticas.index');
        Route::get('/colmenas-por-apiario', [ControllerEstadisticas::class, 'colmenasPorApiario'])->name('estadisticas.colmenas');
        Route::get('/peso-cosecha-por-apiario', [ControllerEstadisticas::class, 'pesoCosechaPorApiario'])->name('estadisticas.cosecha');
        Route::get('/tratamientos-por-apiario', [ControllerEstadisticas::class, 'tratamientosPorApiario'])->name('estadisticas.tratamientos');
        Route::get('/alimentaciones-por-apiario', [ControllerEstadisticas::class, 'alimentacionesPorApiario'])->name('estadisticas.alimentaciones');
    });
// Estadísticas por colmena
Route::get('/estadisticas/colmenas', [ControllerEstadisticasColmenas::class, 'index'])->name('estadisticas.colmenas.index');

Route::get('/estadisticas/colmenas/inspecciones', [ControllerEstadisticasColmenas::class, 'inspecciones'])->name('estadisticas.colmenas.inspecciones');
Route::get('/estadisticas/colmenas/cosecha', [ControllerEstadisticasColmenas::class, 'cosecha'])->name('estadisticas.colmenas.cosecha');
Route::get('/estadisticas/colmenas/tratamientos', [ControllerEstadisticasColmenas::class, 'tratamientos'])->name('estadisticas.colmenas.tratamientos');
Route::get('/estadisticas/colmenas/alimentaciones', [ControllerEstadisticasColmenas::class, 'alimentaciones'])->name('estadisticas.colmenas.alimentaciones');



    // Vista de venta del usuario (listado de sus productos + carrito)
    Route::get('/ventaUsuario', [ControllerVentaUsuario::class, 'metodoVentaUsuario'])->name('venta.usuario');

    // Vista principal de ventas (solo mis productos + mi carrito)
    Route::get('/ventaUsuario', [ControllerVentaUsuario::class, 'metodoVentaUsuario'])
        ->name('venta.usuario');

    // Carrito
    Route::post('/ventaUsuario/cart/add',    [VentaController::class, 'cartAdd'])->name('venta.cart.add');
    Route::post('/ventaUsuario/cart/update', [VentaController::class, 'cartUpdate'])->name('venta.cart.update');
    Route::post('/ventaUsuario/cart/remove', [VentaController::class, 'cartRemove'])->name('venta.cart.remove');

    // Checkout
    Route::post('/ventaUsuario/checkout',    [VentaController::class, 'store'])->name('venta.checkout');
    // reportes
    // Lista de reportes
    Route::get('/reporteUsuario', [ControllerVentaUsuario::class, 'metodoReporteUsuario'])
        ->name('venta.reporte');              // <-- ESTE name

    // Detalle de venta
    Route::get('/reporteUsuario/{venta}', [ControllerVentaUsuario::class, 'mostrarVenta'])
        ->whereNumber('venta')
        ->name('venta.reporte.detalle');

    Route::get('/ventaUsuario', [ControllerVentaUsuario::class, 'metodoVentaUsuario']);
    Route::get('/reporteUsuario', [ControllerVentaUsuario::class, 'metodoReporteUsuario']);
    Route::get('/stockUsuario', [ControllerVentaUsuario::class, 'metodoStockUsuario']);


    Route::get('/mis/dispositivos', [DispositivoWebController::class, 'index'])->name('mis.dispositivos');
    Route::post('/mis/dispositivos', [DispositivoWebController::class, 'store'])->name('mis.dispositivos.store');
    Route::get('/mis/dispositivos/{id}', [DispositivoWebController::class, 'show'])->name('mis.dispositivos.show');
});
