@extends('administrador.inicio')
@section('content')

@php
    use App\Models\TareaPendiente;
    use App\Models\InspeccionColmena;
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();

    // ID lógico del dueño (apicultor principal: para usuario = su id, para colaborador = idusuario)
    $ownerId = $user->ownerId();

    // ================== TAREAS PROGRAMADAS (solo pendientes / en progreso) ==================
    $tareas = TareaPendiente::activas()
        ->where('idUser', $ownerId)                                  // usar ownerId en vez de $user->id
        ->whereIn('estado', ['pendiente', 'enProgreso'])              // SOLO pendientes o en progreso
        ->whereHas('colmena', function ($q) {
            $q->where('estado', 'activo')
              ->whereHas('apiario', function ($q2) {
                  $q2->where('estado', 'activo');
              });
        })
        // urgente > alta > media > baja
        ->orderByRaw("FIELD(prioridad, 'urgente','alta','media','baja')")
        // primero las que tienen fechaFin más cercana (las NULL al final)
        ->orderByRaw("fechaFin IS NULL, fechaFin ASC")
        ->take(6)
        ->get();

    // ================== ÚLTIMA INSPECCIÓN ==================
    if ($user->rol === 'usuario') {
        // DUEÑO: última inspección en cualquiera de SUS apiarios (hecha por él o por colaborador)
        $ultima = InspeccionColmena::whereHas('colmena', function ($q) use ($user) {
                $q->whereHas('apiario', function ($q2) use ($user) {
                    $q2->where('estado', 'activo')
                       ->where('creadoPor', $user->id);   // apiarios del dueño
                });
            })
            ->orderBy('fechaInspeccion', 'desc')
            ->first();
    } else {
        // COLABORADOR: última inspección que ÉL MISMO realizó
        $ultima = InspeccionColmena::where('idUser', $user->id)
            ->whereHas('colmena', function ($q) {
                $q->where('estado', 'activo')
                  ->whereHas('apiario', function ($q2) {
                      $q2->where('estado', 'activo');
                  });
            })
            ->orderBy('fechaInspeccion', 'desc')
            ->first();
    }

    $ultimaFecha = $ultima
        ? \Carbon\Carbon::parse($ultima->fechaInspeccion)->format('d/m/Y')
        : null;
@endphp

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total apiarios</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/colmenar.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ Auth::user()->apiarios()->where('estado', 'activo')->count() }}
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Colmenas totales</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/cajaDeAbejas.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ Auth::user()->colmenasActivas->count() }}
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inspecciones</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/apicultorInsp.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ Auth::user()->cantidadInspecciones() }}  
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Cantidad de sensores</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/sensorTemperatura.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ Auth::user()->dispositivos->count() }}
                    </h1>
                </div>
            </div>
        </div>

        @if(auth()->user()->rol != 'colaborador')
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total productos</h5>
                    </div>
                    <div class="ibox-content">
                        <img src="{{ asset('img/tarro-de-miel.png') }}" alt="Logo" style="width:60px; height:60px;">
                        <h1 class="no-margins">
                            {{ Auth::user()->cantidadProductosActivos() }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total ventas</h5>
                    </div>
                    <div class="ibox-content">
                        <img src="{{ asset('img/ventas.png') }}" alt="Logo" style="width:60px; height:60px;">
                        <h1 class="no-margins">
                            {{ Auth::user()->ventasRealizadas->count() }}
                        </h1>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<div class="row">
    {{-- TAREAS PROGRAMADAS --}}
    <div class="col-lg-7">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Tareas programadas</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover no-margins">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Fecha Fin</th>
                            <th>Prioridad</th>
                            <th>Título</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tareas as $tarea)
                            <tr>
                                {{-- Estado --}}
                                <td>
                                    @if($tarea->estado == 'pendiente')
                                        <span class="label label-primary">Pendiente</span>
                                    @elseif($tarea->estado == 'completada')
                                        <span class="label label-success">Completada</span>
                                    @elseif($tarea->estado == 'enProgreso')
                                        <span class="label label-warning">En Progreso</span>
                                    @elseif($tarea->estado == 'cancelada')
                                        <span class="label label-danger">Cancelada</span>
                                    @elseif($tarea->estado == 'vencida')
                                        <span class="label label-danger">Vencida</span>
                                    @endif
                                </td>

                                {{-- Fecha Fin --}}
                                <td>
                                    {{ $tarea->fechaFin
                                        ? \Carbon\Carbon::parse($tarea->fechaFin)->format('d/m/Y')
                                        : 'N/A' }}
                                </td>

                                {{-- Prioridad --}}
                                <td>
                                    @if($tarea->prioridad == 'baja')
                                        <span class="label label-info">Baja</span>
                                    @elseif($tarea->prioridad == 'media')
                                        <span class="label label-primary">Media</span>
                                    @elseif($tarea->prioridad == 'alta')
                                        <span class="label label-warning">Alta</span>
                                    @elseif($tarea->prioridad == 'urgente')
                                        <span class="label label-danger">Urgente</span>
                                    @endif
                                </td>

                                {{-- Título --}}
                                <td>{{ $tarea->titulo }}</td>

                                {{-- Descripción: Colmena + Apiario --}}
                                <td>
                                    @if($tarea->colmena && $tarea->colmena->apiario)
                                        Colmena #{{ $tarea->colmena->codigo }} - {{ $tarea->colmena->apiario->nombre }}
                                    @elseif($tarea->colmena)
                                        Colmena #{{ $tarea->colmena->codigo }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- ÚLTIMA INSPECCIÓN --}}
    <div class="col-lg-5">
        <div class="ibox float-e-margins">
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <span class="label label-warning pull-right">
                        Colmena #{{ $ultima->colmena->codigo ?? 'N/A' }}
                        -
                        {{ $ultima->colmena->apiario->nombre ?? 'N/A' }}
                    </span>
                    <h5>Última inspección</h5>
                </div>

                {{-- Info colmena / apiario --}}
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-xs-6">
                            <small class="stats-label">Colmena</small>
                            <h4>{{ $ultima->colmena->codigo ?? 'N/A' }}</h4>
                        </div>
                        <div class="col-xs-6">
                            <small class="stats-label">Apiario</small>
                            <h4>{{ $ultima->colmena->apiario->nombre ?? 'N/A' }}</h4>
                        </div>
                    </div>
                </div>

                {{-- FILA 1 --}}
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <small class="stats-label">Fecha inspección</small>
                            <h4>{{ $ultimaFecha ?? 'N/A' }}</h4>
                        </div>

                        <div class="col-xs-4">
                            <small class="stats-label">Estado Colmena</small>
                            <h4 class="text-success">
                                {{ $ultima->estadoOperativo ?? 'N/A' }}
                            </h4>
                        </div>

                        <div class="col-xs-4">
                            <small class="stats-label">Temperamento</small>
                            <h4>{{ $ultima->temperamento ?? 'N/A' }}</h4>
                        </div>
                    </div>
                </div>

                {{-- FILA 2 --}}
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <small class="stats-label">Estado reina</small>
                            <h4>{{ $ultima->estadoReina ?? 'N/A' }}</h4>
                        </div>

                        <div class="col-xs-4">
                            <small class="stats-label">Reserva Miel</small>
                            <h4>{{ $ultima->reservaMiel ?? 'N/A' }}</h4>
                        </div>

                        <div class="col-xs-4">
                            <small class="stats-label">Reserva polen</small>
                            <h4>{{ $ultima->reservaPolen ?? 'N/A' }}</h4>
                        </div>
                    </div>
                </div>

                {{-- FILA 3 - EnfermedadPlaga --}}
                <div class="ibox-content">
                    <div class="row">
                        {{-- Hormigas --}}
                        <div class="col-xs-4">
                            <small class="stats-label">Hormigas</small>
                            @php
                                $hormigas = $ultima ? $ultima->enfermedadPlaga === 'hormigas' : null;
                                $color = $hormigas ? 'label-danger' : 'text-success';
                            @endphp
                            <h4 class="{{ $color }}">{{ $hormigas ? 'Sí' : 'No' }}</h4>
                        </div>

                        {{-- Varroa --}}
                        <div class="col-xs-4">
                            <small class="stats-label">Varroa</small>
                            @php
                                $varroa = $ultima ? $ultima->enfermedadPlaga === 'varroa' : null;
                                $color = $varroa ? 'label-danger' : 'text-success';
                            @endphp
                            <h4 class="{{ $color }}">{{ $varroa ? 'Sí' : 'No' }}</h4>
                        </div>

                        {{-- Loque europea --}}
                        <div class="col-xs-4">
                            <small class="stats-label">Loque europea</small>
                            @php
                                $loque = $ultima ? $ultima->enfermedadPlaga === 'loque_europea' : null;
                                $color = $loque ? 'label-danger' : 'text-success';
                            @endphp
                            <h4 class="{{ $color }}">{{ $loque ? 'Sí' : 'No' }}</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
