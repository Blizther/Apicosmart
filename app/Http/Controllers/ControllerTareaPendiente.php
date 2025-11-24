<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TareaPendiente;
use App\Models\Colmena;

class ControllerTareaPendiente extends Controller
{
    /**
     * Obtener el ID del dueño (apicultor) según quién está logueado.
     * - usuario      => usa su propio id
     * - colaborador  => usa idusuario (dueño)
     */
    private function getOwnerId(): int
    {
        $user = Auth::user();

        if ($user->rol === 'colaborador') {
            return (int) $user->idusuario;
        }

        return (int) $user->id;
    }

    public function index()
    {
        $ownerId = $this->getOwnerId();

        $baseQuery = TareaPendiente::activas()
            ->where('idUser', $ownerId)
            ->whereHas('colmena', function ($q) use ($ownerId) {
                $q->where('estado', 'activo')
                ->where('creadoPor', $ownerId)
                ->whereHas('apiario', function ($q2) {
                    $q2->where('estado', 'activo');
                });
            });

        // LISTA PRINCIPAL PAGINADA
        $tareas = (clone $baseQuery)
            ->with('colmena.apiario')
            ->orderByRaw("CASE WHEN estado = 'completada' THEN 1 ELSE 0 END")
            ->orderByRaw("FIELD(prioridad, 'urgente','alta','media','baja')")
            ->orderByRaw("fechaFin IS NULL, fechaFin ASC")
            ->paginate(10);   // <-- PAGINACIÓN

        // TOTALES
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

        return view('tareapendiente.index', compact(
            'tareas',
            'totalPendientes',
            'totalUrgentes',
            'totalVencidas'
        ));
    }


    public function create()
    {
        $ownerId = $this->getOwnerId();

        // Colmenas SOLO del dueño, activas y de apiarios activos
        $colmenas = Colmena::where('creadoPor', $ownerId)
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
        $ownerId = $this->getOwnerId();

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
            'fechaInicio.required'               => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'                  => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'            => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'fechaRecordatorio.before_or_equal'  => 'La fecha de recordatorio no puede ser posterior a la fecha de fin.',
        ]);

        // Verificar que la colmena está activa, su apiario activo y que pertenece al dueño
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
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
            'idUser'            => $ownerId,          // dueño de la tarea
            'idColmena'         => $request->idColmena,
            'titulo'            => $request->titulo,
            'descripcion'       => $request->descripcion,
            'prioridad'         => $request->prioridad,
            'estado'            => $request->estado,
            'fechaInicio'       => $request->fechaInicio,
            'fechaFin'          => $request->fechaFin,
            'fechaRecordatorio' => $request->fechaRecordatorio,
            'creadoPor'         => Auth::id(),        // quien creó (usuario o colaborador)
            'tipo'              => $request->tipo,
            'eliminado'         => 'activo',
        ]);

        return redirect()->route('tarea.index')->with('success', 'Tarea pendiente registrada correctamente.');
    }

    public function edit($id)
    {
        $ownerId = $this->getOwnerId();

        $tarea = TareaPendiente::activas()
            ->where('idTareaPendiente', $id)
            ->where('idUser', $ownerId)
            ->firstOrFail();

        $colmenas = Colmena::where('creadoPor', $ownerId)
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
        $ownerId = $this->getOwnerId();

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
            'fechaInicio.required'               => 'La fecha de inicio es obligatoria.',
            'fechaFin.required'                  => 'La fecha de fin es obligatoria.',
            'fechaFin.after_or_equal'            => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'fechaRecordatorio.before_or_equal'  => 'La fecha de recordatorio no puede ser posterior a la fecha de fin.',
        ]);

        // Verificar colmena activa + apiario activo + que sea del dueño
        $colmena = Colmena::where('idColmena', $request->idColmena)
            ->where('estado', 'activo')
            ->where('creadoPor', $ownerId)
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
            ->where('idUser', $ownerId)
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
        // 🔒 El colaborador NO puede eliminar tareas
        if (Auth::user()->rol === 'colaborador') {
            abort(403, 'No tienes permiso para eliminar tareas pendientes.');
        }

        $ownerId = $this->getOwnerId();

        $tarea = TareaPendiente::activas()
            ->where('idTareaPendiente', $id)
            ->where('idUser', $ownerId)
            ->firstOrFail();

        $tarea->eliminado = 'inactivo';
        $tarea->save();

        return redirect()->route('tarea.index')->with('successdelete', 'Tarea pendiente eliminada correctamente.');
    }
}
