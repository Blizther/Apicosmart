<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TareaPendiente;

class ControllerTareaPendiente extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $tareas = TareaPendiente::activas()
            ->where('idUser', $userId)
            ->orderByRaw("FIELD(prioridad, 'urgente','alta','media','baja')")
            ->orderBy('fechaFin', 'asc')
            ->get();

        $totalPendientes = TareaPendiente::activas()
            ->where('idUser', $userId)
            ->whereIn('estado', ['pendiente', 'enProgreso'])
            ->count();

        $totalUrgentes = TareaPendiente::activas()
            ->where('idUser', $userId)
            ->whereIn('estado', ['pendiente', 'enProgreso'])
            ->where('prioridad', 'urgente')
            ->count();

        $totalVencidas = TareaPendiente::activas()
            ->where('idUser', $userId)
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
        return view('tareapendiente.create');
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

        return view('tareapendiente.edit', compact('tarea'));
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
