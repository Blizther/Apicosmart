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
use App\Http\Controllers\ControllerCosecha;
use App\Http\Controllers\ControllerTratamiento;
use App\Http\Controllers\ControllerAlimentacion;
use App\Http\Controllers\ControllerEstadisticas;
use App\Http\Controllers\ControllerEstadisticasColmenas;
use App\Http\Controllers\ControllerTareaPendiente;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ====== RUTAS ADMINISTRADOR SOLO ======
Route::middleware(['auth', 'rol:administrador'])->group(function () {

    Route::get('/administrador/inicio', function () {
        return view('administrador.dashboard');
    })->name('administrador.dashboard');

    // Dispositivos fabricados
    Route::get('/administrador/dispositivos-fabricados',       [DispositivoFabricadoController::class, 'index'])->name('fabricados.index');
    Route::get('/administrador/dispositivos-fabricados/crear', [DispositivoFabricadoController::class, 'create'])->name('fabricados.create');
    Route::post('/administrador/dispositivos-fabricados',      [DispositivoFabricadoController::class, 'store'])->name('fabricados.store');
});


// ====== ADMIN + USUARIO: ADMINISTRAR USUARIOS ======
Route::middleware(['auth', 'rol:administrador|usuario'])->group(function () {

    Route::resource('users', UserController::class);
    Route::get('users/{id}/permisos', [UserController::class, 'editpermiso'])->name('users.permiso');
    Route::put('users/{id}/guardarpermiso', [UserController::class, 'updatepermiso'])->name('users.updatepermiso');
});


// ====== USUARIO + COLABORADOR: DASHBOARD Y MÓDULOS PRINCIPALES ======
Route::middleware(['auth', 'rol:usuario|colaborador'])->group(function () {

    // Dashboard usuario/colaborador
    Route::get('/usuario/inicio', function () {
        return view('usuario.dashboard');
    })->name('usuario.inicio');

    // ========= SECCIÓN APIARIO =========
    Route::get('/apiario', [ControllerApiario::class, 'index'])->name('apiario.index');

    // Crear / editar / eliminar apiario -> SOLO USUARIO (no colaborador)
    Route::get('/apiario/crearapiario',       [ControllerApiario::class, 'create'])->middleware('rol:usuario')->name('apiario.create');
    Route::post('/apiario/guardarapiario',    [ControllerApiario::class, 'store'])->middleware('rol:usuario')->name('apiario.store');
    Route::delete('/apiario/eliminarapiario/{id}', [ControllerApiario::class, 'destroy'])->middleware('rol:usuario')->name('apiario.destroy');
    Route::get('/apiario/{id}/editarapiario', [ControllerApiario::class, 'edit'])->middleware('rol:usuario')->name('apiario.edit');
    Route::put('/apiario/editarapiario/{id}', [ControllerApiario::class, 'update'])->middleware('rol:usuario')->name('apiario.update');

    // Ver colmenas de un apiario (usuario y colaborador)
    Route::get('/apiario/{id}/verapiario', [ControllerApiario::class, 'vercolmenas'])->name('apiario.verapiario');

    // ========= SECCIÓN COLMENAS =========
    // Crear varias colmenas a la vez -> SOLO USUARIO
    Route::get('/colmenas/crear-lote',  [ControllerColmena::class, 'createLote'])->middleware('rol:usuario')->name('colmenas.createLote');
    Route::post('/colmenas/guardar-lote', [ControllerColmena::class, 'storeLote'])->middleware('rol:usuario')->name('colmenas.storeLote');
    Route::get('/colmenas/proximo-codigo/{apiario}', [ControllerColmena::class, 'proximoCodigo'])->middleware('rol:usuario');


    // Obtener el siguiente código de colmena según el apiario -> SOLO USUARIO
    Route::get('/colmenas/proximo-codigo/{idApiario}', [ControllerColmena::class, 'proximoCodigo'])->middleware('rol:usuario')->name('colmenas.proximoCodigo');

    // Resource de colmenas: ambos roles, pero luego restringimos acciones en el controlador
    Route::resource('/colmenas', ControllerColmena::class);
    Route::get('/colmenas/{id}/guardarcolmena', [ControllerColmena::class, 'update'])->name('colmenas.guardar');

    // ========= SECCIÓN INSPECCIONES =========
    Route::get('/colmenas/{idColmena}/inspecciones',          [ControllerInspeccionColmena::class, 'index'])->name('inspeccion.index');
    Route::get('/colmenas/{idColmena}/inspecciones/crear',    [ControllerInspeccionColmena::class, 'create'])->name('inspeccion.create');
    Route::post('/colmenas/{idColmena}/inspecciones/guardar', [ControllerInspeccionColmena::class, 'store'])->name('inspeccion.store');
    Route::get('/inspecciones/{id}/editar',                   [ControllerInspeccionColmena::class, 'edit'])->name('inspeccion.edit');
    Route::put('/inspecciones/{id}/actualizar',               [ControllerInspeccionColmena::class, 'update'])->name('inspeccion.update');

    // Eliminar inspección -> SOLO USUARIO
    Route::delete('/inspecciones/{id}/eliminar', [ControllerInspeccionColmena::class, 'destroy'])
        ->middleware('rol:usuario')
        ->name('inspeccion.destroy');


    // ========= SECCIÓN COSECHAS =========
    Route::get('/cosechas',               [ControllerCosecha::class, 'index'])->name('cosechas.index');
    Route::get('/cosechas/crear',         [ControllerCosecha::class, 'create'])->name('cosechas.create');
    Route::post('/cosechas/guardar',      [ControllerCosecha::class, 'store'])->name('cosechas.store');
    Route::get('/cosechas/{id}/editar',   [ControllerCosecha::class, 'edit'])->name('cosechas.edit');
    Route::put('/cosechas/{id}/actualizar', [ControllerCosecha::class, 'update'])->name('cosechas.update');

    // Eliminar cosecha -> SOLO USUARIO
    Route::delete('/cosechas/{id}/eliminar', [ControllerCosecha::class, 'destroy'])
        ->middleware('rol:usuario')
        ->name('cosechas.destroy');


    // ========= SECCIÓN TRATAMIENTOS =========
    Route::get('/tratamiento',           [ControllerTratamiento::class, 'index'])->name('tratamiento.index');
    Route::get('/tratamiento/crear',     [ControllerTratamiento::class, 'create'])->name('tratamiento.create');
    Route::post('/tratamiento/guardar',  [ControllerTratamiento::class, 'store'])->name('tratamiento.store');
    Route::get('/tratamiento/{id}/editar', [ControllerTratamiento::class, 'edit'])->name('tratamiento.edit');
    Route::put('/tratamiento/{id}/actualizar', [ControllerTratamiento::class, 'update'])->name('tratamiento.update');

    // Eliminar tratamiento -> SOLO USUARIO
    Route::delete('/tratamiento/{id}/eliminar', [ControllerTratamiento::class, 'destroy'])
        ->middleware('rol:usuario')
        ->name('tratamiento.destroy');


    // ========= SECCIÓN ALIMENTACIÓN =========
    Route::get('/alimentacion',           [ControllerAlimentacion::class, 'index'])->name('alimentacion.index');
    Route::get('/alimentacion/crear',     [ControllerAlimentacion::class, 'create'])->name('alimentacion.create');
    Route::post('/alimentacion/guardar',  [ControllerAlimentacion::class, 'store'])->name('alimentacion.store');
    Route::get('/alimentacion/{id}/editar', [ControllerAlimentacion::class, 'edit'])->name('alimentacion.edit');
    Route::put('/alimentacion/{id}/actualizar', [ControllerAlimentacion::class, 'update'])->name('alimentacion.update');

    // Eliminar alimentación -> SOLO USUARIO
    Route::delete('/alimentacion/{id}/eliminar', [ControllerAlimentacion::class, 'destroy'])
        ->middleware('rol:usuario')
        ->name('alimentacion.destroy');


    // ========= SECCIÓN TAREAS PENDIENTES =========
    Route::get('/tarea-pendiente',            [ControllerTareaPendiente::class, 'index'])->name('tarea.index');
    Route::get('/tarea-pendiente/crear',      [ControllerTareaPendiente::class, 'create'])->name('tarea.create');
    Route::post('/tarea-pendiente/guardar',   [ControllerTareaPendiente::class, 'store'])->name('tarea.store');
    Route::get('/tarea-pendiente/{id}/editar',[ControllerTareaPendiente::class, 'edit'])->name('tarea.edit');
    Route::put('/tarea-pendiente/{id}/actualizar', [ControllerTareaPendiente::class, 'update'])->name('tarea.update');

    // Eliminar tarea pendiente -> SOLO USUARIO
    Route::delete('/tarea-pendiente/{id}/eliminar', [ControllerTareaPendiente::class, 'destroy'])
        ->middleware('rol:usuario')
        ->name('tarea.destroy');


    // ========= ESTADÍSTICAS =========
    Route::prefix('estadisticas')->group(function () {
        Route::get('/',                           [ControllerEstadisticas::class, 'index'])->name('estadisticas.index');
        Route::get('/colmenas-por-apiario',       [ControllerEstadisticas::class, 'colmenasPorApiario'])->name('estadisticas.colmenas');
        Route::get('/peso-cosecha-por-apiario',   [ControllerEstadisticas::class, 'pesoCosechaPorApiario'])->name('estadisticas.cosecha');
        Route::get('/tratamientos-por-apiario',   [ControllerEstadisticas::class, 'tratamientosPorApiario'])->name('estadisticas.tratamientos');
        Route::get('/alimentaciones-por-apiario', [ControllerEstadisticas::class, 'alimentacionesPorApiario'])->name('estadisticas.alimentaciones');
    });

    Route::get('/estadisticas/colmenas',               [ControllerEstadisticasColmenas::class, 'index'])->name('estadisticas.colmenas.index');
    Route::get('/estadisticas/colmenas/inspecciones',  [ControllerEstadisticasColmenas::class, 'inspecciones'])->name('estadisticas.colmenas.inspecciones');
    Route::get('/estadisticas/colmenas/cosecha',       [ControllerEstadisticasColmenas::class, 'cosecha'])->name('estadisticas.colmenas.cosecha');
    Route::get('/estadisticas/colmenas/tratamientos',  [ControllerEstadisticasColmenas::class, 'tratamientos'])->name('estadisticas.colmenas.tratamientos');
    Route::get('/estadisticas/colmenas/alimentaciones',[ControllerEstadisticasColmenas::class, 'alimentaciones'])->name('estadisticas.colmenas.alimentaciones');
});


// ====== SOLO USUARIO: PRODUCTOS, VENTAS, REPORTES, DISPOSITIVOS ======
Route::middleware(['auth', 'rol:usuario'])->group(function () {

    // Productos
    Route::get('/productos',                 [ControllerProducto::class, 'metodoProductos']);
    Route::get('/productos/crearproducto',   [ControllerProducto::class, 'nuevoproducto']);
    Route::post('/productos/guardarproducto',[ControllerProducto::class, 'nuevoproductobd']);
    Route::delete('/productos/eliminarproducto/{id}', [ControllerProducto::class, 'eliminarproductobd'])->name('productos.destroy');
    Route::get('/productos/{id}/editarproducto',      [ControllerProducto::class, 'editarProducto'])->name('productos.editar');
    Route::put('/productos/editarproducto/{id}',      [ControllerProducto::class, 'actualizarProducto'])->name('productos.actualizar');

    // Vista principal de ventas (solo mis productos + mi carrito)
    Route::get('/ventaUsuario', [ControllerVentaUsuario::class, 'metodoVentaUsuario'])->name('venta.usuario');

    // Carrito
    Route::post('/ventaUsuario/cart/add',    [VentaController::class, 'cartAdd'])->name('venta.cart.add');
    Route::post('/ventaUsuario/cart/update', [VentaController::class, 'cartUpdate'])->name('venta.cart.update');
    Route::post('/ventaUsuario/cart/remove', [VentaController::class, 'cartRemove'])->name('venta.cart.remove');

    // Checkout
    Route::post('/ventaUsuario/checkout',    [VentaController::class, 'store'])->name('venta.checkout');

    // Reportes de venta
    Route::get('/reporteUsuario', [ControllerVentaUsuario::class, 'metodoReporteUsuario'])->name('venta.reporte');
    Route::get('/reporteUsuario/{venta}', [ControllerVentaUsuario::class, 'mostrarVenta'])
        ->whereNumber('venta')
        ->name('venta.reporte.detalle');

    // Stock
    Route::get('/stockUsuario', [ControllerVentaUsuario::class, 'metodoStockUsuario']);

    // ========= DISPOSITIVOS DEL USUARIO (SOLO USUARIO) =========
    Route::get('/mis/dispositivos',        [DispositivoWebController::class, 'index'])->name('mis.dispositivos');
    Route::post('/mis/dispositivos',       [DispositivoWebController::class, 'store'])->name('mis.dispositivos.store');
    Route::get('/mis/dispositivos/{id}',   [DispositivoWebController::class, 'show'])->name('mis.dispositivos.show');
});
