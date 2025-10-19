<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ControllerInspeccionColmena extends Controller
{
    //
    public function store(Request $request)
{
    // Tomamos idColmena del hidden/route y el usuario autenticado
    $idColmena = $request->input('colmena_id')
        ?? $request->input('idColmena')
        ?? $request->route('id');

    $idUser = auth()->id() ?? $request->input('idUser');

    // VALIDACIÓN: solo tipos y longitudes según tu SQL
    $data = $request->validate([
        'temperamento'           => ['nullable','string','max:45'],
        'intensidadImportacion'  => ['nullable','string','max:45'], // en BD es "IntensidadImportacion"
        'estadoReyna'            => ['nullable','string','max:45'],
        'reservaMiel'            => ['nullable','string','max:45'],
        'reservaPolen'           => ['nullable','string','max:45'],

        // checkboxes llegan como array -> luego haremos join a varchar(45)
        'celdasReales'           => ['nullable','array'],
        'celdasReales.*'         => ['nullable','string','max:45'],
        'patronPostura'          => ['nullable','array'],
        'patronPostura.*'        => ['nullable','string','max:45'],
        'enfermedadPlaga'        => ['nullable','array'],
        'enfermedadPlaga.*'      => ['nullable','string','max:45'],

        'notas'                  => ['nullable','string'], // TEXT
    ]);
    $data['celdasReales'] = isset($data['celdasReales']) ? implode(', ', $data['celdasReales']) : null;
    // Validaciones mínimas de claves
    if (empty($idColmena) || empty($idUser)) {
        return back()->withErrors('Falta id de colmena o usuario.')->withInput();
    }

    // Une arrays de checkboxes a "valor1, valor2" y limita a 45 chars
    $join = function ($v) {
        if (is_array($v)) $v = implode(', ', $v);
        return $v !== null ? Str::limit($v, 45, '') : null;
    };

    // Payload EXACTO a los nombres de tus columnas
    $row = [
        'idColmena'             => (int) $idColmena,
        'idUser'                => (int) $idUser,

        'temperamento'          => $data['temperamento'] ?? null,
        'IntensidadImportacion' => $data['intensidadImportacion'] ?? null, // mapeo de name -> columna
        'estadoReyna'           => $data['estadoReyna'] ?? null,
        'reservaMiel'           => $data['reservaMiel'] ?? null,
        'reservaPolen'          => $data['reservaPolen'] ?? null,

        'celdasReales'          => $join($data['celdasReales'] ?? null),
        'patronPostura'         => $join($data['patronPostura'] ?? null),
        'enfermedadPlaga'       => $join($data['enfermedadPlaga'] ?? null),

        'notas'                 => $data['notas'] ?? null,
        // fechas usan DEFAULT de la BD
    ];

    try {
        DB::table('inspeccioncolmena')->insert($row);
    } catch (\Throwable $e) {
        return back()->withErrors('No se pudo guardar la inspección: '.$e->getMessage())->withInput();
    }

    return redirect()
        ->route('colmenas.verinspeccion', ['id' => $idColmena])
        ->with('success', 'Inspección registrada correctamente.');
}
}
