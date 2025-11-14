<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TareaPendiente;
use App\Models\Colmena;

class ControllerTareaPendiente extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Base: tareas del usuario, no eliminadas, ligadas a colmenas y apiarios activos
        $baseQuery = TareaPendiente::activas()
            ->where('idUser', $userId)
            ->whereHas('colmena', function ($q) {
                $q->where('estado', 'activo')
                ->whereHas('apiario', function ($q2) {
                    $q2->where('estado', 'activo');
                });
            });

        // LISTA PRINCIPAL
        $tareas = (clone $baseQuery)
            ->with('colmena.apiario')
            // 1) Completadas al final
            ->orderByRaw("CASE WHEN estado = 'completada' THEN 1 ELSE 0 END")
            // 2) Prioridad: urgente > alta > media > baja
            ->orderByRaw("FIELD(prioridad, 'urgente','alta','media','baja')")
            // 3) Fecha fin más cercana primero (las NULL al final)
            ->orderByRaw("fechaFin IS NULL, fechaFin ASC")
            ->get();

        // TOTALES (se quedan igual)
        $totalPendientes = (clone $baseQuery)
            ->whereIn('estado', ['pendiente', 'enProgreso'])
            ->count();

        $totalUrgentes = (clone $baseQuery)
            ->whereIn('estado', ['pendiente', 'enProgreso'])
            ->where('prioridad', 'urgente')
            ->count();

        $totalVencidas = (clone $baseQuery)
            ->where('estado', 'vencida')
            ->count();

        return view('tareapendiente.index', [
            'tareas'          => $tareas,
            'totalPendientes' => $totalPendientes,
            'totalUrgentes'   => $totalUrgentes,
            'totalVencidas'   => $totalVencidas,
        ]);
    }


    public function create()
    {
        // Colmenas SOLO del usuario, activas y de apiarios activos
        $colmenas = Colmena::where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            })
            ->with('apiario')
            ->get();

        return view('tareapendiente.create', compact('colmenas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idColmena'         => 'required|exists:colmena,idColmena',
            'tipo'              => 'required|in:inspeccion,cosecha,tratamiento,alimentacion,mantenimiento',
            'titulo'            => 'required|string|max:100',
            'descripcion'       => 'nullable|string',
            'prioridad'         => 'required|in:baja,media,alta,urgente',
            'estado'            => 'required|in:pendiente,enProgreso,completada,cancelada,vencida',
            'fechaInicio'       => 'required|date',
            'fechaFin'          => 'required|date|after_or_equal:fechaInicio',
            'fechaRecordatorio' => 'nullable|date|before_or_equal:fechaFin',
        ], [
            'fechaInicio.required'        => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'           => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'     => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'fechaRecordatorio.before_or_equal' => 'La fecha de recordatorio no puede ser posterior a la fecha de fin.',
        ]);

        // Verificar que la colmena está activa y su apiario también
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            })
            ->first();

        if (!$colmena) {
            return back()
                ->withErrors(['idColmena' => 'La colmena seleccionada no es válida o pertenece a un apiario inactivo.'])
                ->withInput();
        }

        TareaPendiente::create([
            'idUser'            => Auth::id(),
            'idColmena'         => $request->idColmena,
            'titulo'            => $request->titulo,
            'descripcion'       => $request->descripcion,
            'prioridad'         => $request->prioridad,
            'estado'            => $request->estado,
            'fechaInicio'       => $request->fechaInicio,
            'fechaFin'          => $request->fechaFin,
            'fechaRecordatorio' => $request->fechaRecordatorio,
            'creadoPor'         => Auth::id(),
            'tipo'              => $request->tipo,
            'eliminado'         => 'activo',
        ]);

        return redirect()->route('tarea.index')->with('success', 'Tarea pendiente registrada correctamente.');
    }

    public function edit($id)
    {
        $tarea = TareaPendiente::activas()
            ->where('idTareaPendiente', $id)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', Auth::id())
            ->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            })
            ->with('apiario')
            ->get();

        return view('tareapendiente.edit', compact('tarea', 'colmenas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idColmena'         => 'required|exists:colmena,idColmena',
            'tipo'              => 'required|in:inspeccion,cosecha,tratamiento,alimentacion,mantenimiento',
            'titulo'            => 'required|string|max:100',
            'descripcion'       => 'nullable|string',
            'prioridad'         => 'required|in:baja,media,alta,urgente',
            'estado'            => 'required|in:pendiente,enProgreso,completada,cancelada,vencida',
            'fechaInicio'       => 'required|date',
            'fechaFin'          => 'required|date|after_or_equal:fechaInicio',
            'fechaRecordatorio' => 'nullable|date|before_or_equal:fechaFin',
        ], [
            'fechaInicio.required'        => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'           => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'     => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'fechaRecordatorio.before_or_equal' => 'La fecha de recordatorio no puede ser posterior a la fecha de fin.',
        ]);

        // Verificar colmena activa + apiario activo
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            })
            ->first();

        if (!$colmena) {
            return back()
                ->withErrors(['idColmena' => 'La colmena seleccionada no es válida o pertenece a un apiario inactivo.'])
                ->withInput();
        }

        $tarea = TareaPendiente::activas()
            ->where('idTareaPendiente', $id)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        $tarea->update([
            'idColmena'         => $request->idColmena,
            'titulo'            => $request->titulo,
            'descripcion'       => $request->descripcion,
            'prioridad'         => $request->prioridad,
            'estado'            => $request->estado,
            'fechaInicio'       => $request->fechaInicio,
            'fechaFin'          => $request->fechaFin,
            'fechaRecordatorio' => $request->fechaRecordatorio,
            'tipo'              => $request->tipo,
        ]);

        return redirect()->route('tarea.index')->with('successedit', 'Tarea pendiente actualizada correctamente.');
    }

    public function destroy($id)
    {
        $tarea = TareaPendiente::activas()
            ->where('idTareaPendiente', $id)
            ->where('idUser', Auth::id())
            ->firstOrFail();

        $tarea->eliminado = 'inactivo';
        $tarea->save();

        return redirect()->route('tarea.index')->with('successdelete', 'Tarea pendiente eliminada correctamente.');
    }
}
