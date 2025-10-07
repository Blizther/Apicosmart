<?php

use App\Http\Controllers\Api\SensorIngestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {

    // Salud / ping
    Route::get('/health', function () {
        return response()->json(['ok' => true, 'service' => 'api', 'version' => 'v1']);
    });

    // ========== APIARIOS ==========
    // GET /api/v1/apiarios
    Route::get('/apiarios', function (Request $req) {
        try {
            // Tip: si quieres paginar, agrega ?limit=50&offset=0
            $limit  = (int)($req->query('limit', 100));
            $offset = (int)($req->query('offset', 0));

            $rows = DB::table('apiario')
                ->select('*')
                ->orderByDesc('idApiario')
                ->limit($limit)->offset($offset)
                ->get();

            return response()->json($rows);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // GET /api/v1/apiarios/{id}
    Route::get('/apiarios/{id}', function ($id) {
        try {
            $row = DB::table('apiario')->where('idApiario', $id)->first();
            if (!$row) return response()->json(['message' => 'No encontrado'], 404);
            return response()->json($row);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // ========== COLMENAS ==========
    // GET /api/v1/colmenas
    Route::get('/colmenas', function (Request $req) {
        try {
            $limit  = (int)($req->query('limit', 100));
            $offset = (int)($req->query('offset', 0));

            // Si tu tabla tiene 'idApiario', puedes filtrar opcionalmente:
            $idApiario = $req->query('idApiario');

            $q = DB::table('colmena')->select('*')->orderByDesc('idColmena');
            if (!is_null($idApiario) && $idApiario !== '') {
                $q->where('idApiario', $idApiario);
            }

            $rows = $q->limit($limit)->offset($offset)->get();
            return response()->json($rows);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // GET /api/v1/colmenas/{id}
    Route::get('/colmenas/{id}', function ($id) {
        try {
            $row = DB::table('colmena')->where('idColmena', $id)->first();
            if (!$row) return response()->json(['message' => 'No encontrado'], 404);
            return response()->json($row);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // ========== PRODUCTOS ==========
    // GET /api/v1/productos
    Route::get('/productos', function (Request $req) {
        try {
            $limit  = (int)($req->query('limit', 100));
            $offset = (int)($req->query('offset', 0));
            $texto  = $req->query('texto');

            $q = DB::table('productos')->select('*')->orderByDesc('id');
            if (!is_null($texto) && $texto !== '') {
                // Si tu columna de descripción se llama distinto, lo ajustamos luego.
                $q->where('descripcion', 'like', "%{$texto}%");
            }

            $rows = $q->limit($limit)->offset($offset)->get();
            return response()->json($rows);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // GET /api/v1/productos/{id}
    Route::get('/productos/{id}', function ($id) {
        try {
            $row = DB::table('productos')->where('id', $id)->first();
            if (!$row) return response()->json(['message' => 'No encontrado'], 404);
            return response()->json($row);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // ========== VENTAS ==========
    // GET /api/v1/ventas
    Route::get('/ventas', function (Request $req) {
        try {
            $limit  = (int)($req->query('limit', 100));
            $offset = (int)($req->query('offset', 0));
            $usuario = $req->query('usuario'); // si tu campo es idUser

            $q = DB::table('ventas')->select('*')->orderByDesc('id');
            if (!is_null($usuario) && $usuario !== '') {
                $q->where('idUser', $usuario);
            }

            $rows = $q->limit($limit)->offset($offset)->get();
            return response()->json($rows);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // GET /api/v1/ventas/{id}  (con detalles + producto si existen esos nombres)
    Route::get('/ventas/{id}', function ($id) {
        try {
            $venta = DB::table('ventas')->where('id', $id)->first();
            if (!$venta) return response()->json(['message' => 'No encontrado'], 404);

            // Traer detalles (ajusta nombres si difieren)
            $detalles = DB::table('detalles')
                ->where('idVenta', $id)
                ->get();

            // Si quieres adjuntar info de producto en cada detalle:
            // (solo funcionará si 'idProducto' existe en detalles y 'productos.id' existe)
            $detalles = $detalles->map(function ($d) {
                $prod = DB::table('productos')->where('id', $d->idProducto ?? null)->first();
                $d->producto = $prod;
                return $d;
            });

            return response()->json(['venta' => $venta, 'detalles' => $detalles]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    Route::get('/health', fn() => response()->json(['ok' => true, 'service' => 'api', 'version' => 'v1']));
    Route::post('/sensores', [SensorIngestController::class, 'store']);
});
