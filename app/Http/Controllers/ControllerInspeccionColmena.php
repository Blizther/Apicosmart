<?php

namespace App\Http\Controllers;

use App\Models\InspeccionColmena;
use App\Models\Colmena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerInspeccionColmena extends Controller
{
    /**
     * Reglas de validación comunes (crear + editar)
     */
    private function validar(Request $request)
    {
        return $request->validate([
            // Fecha de inspección: obligatoria, formato fecha, no puede ser futura
            'fechaInspeccion'       => 'required|date|before_or_equal:today',

            // Selects con valores fijos (coinciden con tus ENUM de la BD)
            'estadoOperativo'       => 'required|in:activa,inactiva,zanganera,huerfana,enferma',
            'temperamento'          => 'required|in:muy_tranquila,tranquila,mediana,defensiva,muy_defensiva',
            'intensidadImportacion' => 'required|in:muy_baja,baja,media,alta,muy_alta',
            'estadoReina'           => 'required|in:activa_buena_postura,postura_reducida,vieja,no_vista,ausente',

            'reservaMiel'           => 'required|in:sin_reservas,baja,media,alta_muchas_reservas',
            'reservaPolen'          => 'required|in:sin_reservas,baja,media,alta_mucho_polen',

            'celdasReales'          => 'required|in:no_hay,en_progresion,tapadas,destruidas,varias_enjambron',

            'patronPostura'         => 'required|in:patron_cerrado,larvas_amarillas,huecos_en_marco,cria_zangano_abundante,mucha_cria_operculada,sin_corona_miel,sospecha_loque',

            'enfermedadPlaga'       => 'required|in:ninguna,loque_europea,loque_americana,varroa,hormigas,otra',

            // Notas opcionales
            'notas'                 => 'nullable|string|max:500',
        ]);
    }

    /**
     * Lista de inspecciones de UNA colmena
     */
    public function index($idColmena)
    {
        $colmena = Colmena::with('apiario')->findOrFail($idColmena);

        $inspecciones = InspeccionColmena::where('idColmena', $idColmena)
            ->orderByDesc('fechaInspeccion')   // primero la fecha más reciente
            ->orderByDesc('id')                // luego por ID por si hay misma fecha
            ->get();

        return view('inspeccion.index', [
            'inspecciones'  => $inspecciones,
            'id'            => $colmena->idColmena,
            'codigoColmena' => $colmena->codigo,
            'nombreApiario' => $colmena->apiario->nombre ?? 'Sin apiario',
        ]);
    }

    /**
     * Formulario para crear una inspección de UNA colmena
     */
    public function create($idColmena)
    {
        $colmena = Colmena::with('apiario')->findOrFail($idColmena);

        return view('inspeccion.create', [
            'id'            => $colmena->idColmena,
            'codigoColmena' => $colmena->codigo,
            'nombreApiario' => $colmena->apiario->nombre ?? 'Sin apiario',
        ]);
    }

    /**
     * Guardar nueva inspección
     */
    public function store(Request $request, $idColmena)
    {
        $colmena = Colmena::findOrFail($idColmena);
        $userId  = Auth::id();

        $data = $this->validar($request);

        $insp = new InspeccionColmena();
        $insp->idColmena              = $colmena->idColmena;
        $insp->idUser                 = $userId;
        $insp->fechaInspeccion        = $data['fechaInspeccion'];
        $insp->estadoOperativo        = $data['estadoOperativo'];
        $insp->temperamento           = $data['temperamento'];
        $insp->intensidadImportacion  = $data['intensidadImportacion'];
        $insp->estadoReina            = $data['estadoReina'];
        $insp->reservaMiel            = $data['reservaMiel'];
        $insp->reservaPolen           = $data['reservaPolen'];
        $insp->celdasReales           = $data['celdasReales'];
        $insp->patronPostura          = $data['patronPostura'];
        $insp->enfermedadPlaga        = $data['enfermedadPlaga'];
        $insp->notas                  = $data['notas'] ?? null;

        $insp->save();

        return redirect()
            ->route('inspeccion.index', $colmena->idColmena)
            ->with('success', 'Inspección registrada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $inspeccion = InspeccionColmena::with('colmena.apiario')->findOrFail($id);
        $colmena    = $inspeccion->colmena;

        return view('inspeccion.edit', [
            'inspeccion'    => $inspeccion,
            'id'            => $colmena->idColmena,
            'codigoColmena' => $colmena->codigo,
            'nombreApiario' => $colmena->apiario->nombre ?? 'Sin apiario',
        ]);
    }

    /**
     * Actualizar inspección
     */
    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionColmena::findOrFail($id);
        $data       = $this->validar($request);

        $inspeccion->fechaInspeccion        = $data['fechaInspeccion'];
        $inspeccion->estadoOperativo        = $data['estadoOperativo'];
        $inspeccion->temperamento           = $data['temperamento'];
        $inspeccion->intensidadImportacion  = $data['intensidadImportacion'];
        $inspeccion->estadoReina            = $data['estadoReina'];
        $inspeccion->reservaMiel            = $data['reservaMiel'];
        $inspeccion->reservaPolen           = $data['reservaPolen'];
        $inspeccion->celdasReales           = $data['celdasReales'];
        $inspeccion->patronPostura          = $data['patronPostura'];
        $inspeccion->enfermedadPlaga        = $data['enfermedadPlaga'];
        $inspeccion->notas                  = $data['notas'] ?? null;

        $inspeccion->save();

        return redirect()
            ->route('inspeccion.index', $inspeccion->idColmena)
            ->with('successedit', 'Inspección actualizada correctamente.');
    }

    /**
     * Eliminar inspección
     */
    public function destroy($id)
    {
        $inspeccion = InspeccionColmena::findOrFail($id);
        $colmenaId  = $inspeccion->idColmena;

        $inspeccion->delete();

        return redirect()
            ->route('inspeccion.index', $colmenaId)
            ->with('successdelete', 'Inspección eliminada correctamente.');
    }
}
