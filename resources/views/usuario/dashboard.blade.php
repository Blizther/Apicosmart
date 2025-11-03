@extends('usuario.inicio')
@section('content')
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
                            {{ Auth::user()->apiarios->count() }}
                        </h1>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        
                        <h5>total colmenas</h5>
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
                        <h5>cantidad de sensores</h5>
                        
                    </div>
                    <div class="ibox-content">
                        <img src="{{ asset('img/sensorTemperatura.png') }}" alt="Logo" style="width:60px; height:60px;">
                        <h1 class="no-margins">
                            {{ Auth::user()->dispositivos->count() }}
                        </h1>
                        
                    </div>

                </div>
            </div>
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        
                        <h5>total productos</h5>
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
                        
                        <h5>total ventas</h5>
                    </div>
                    <div class="ibox-content">
                        <img src="{{ asset('img/ventas.png') }}" alt="Logo" style="width:60px; height:60px;">
                        <h1 class="no-margins">
                            {{ Auth::user()->ventasRealizadas->count() }}
                        </h1>
                        
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="row">
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
                                                <th>titulo</th>
                                                <th>Descripcion</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <!-- Recorre las últimas 5 tareas programadas del usuario autenticado -->
                                            @foreach(Auth::user()->tareasPendientes()->latest()->take(6)->get() as $tarea)
                                                <tr>
                                                    <td>
                                                        <!-- se tienen en cuenta cuatro estados, pendiente, completada, en progreso y cancelada -->
                                                        @if($tarea->estado == 'pendiente')
                                                            <span class="label label-primary">Pendiente</span>
                                                        @elseif($tarea->estado == 'completada')
                                                            <span class="label label-success">Completada</span>
                                                        @elseif($tarea->estado == 'enProgreso')
                                                            <span class="label label-warning">En Progreso</span>
                                                        @elseif($tarea->estado == 'cancelada')
                                                            <span class="label label-danger">Cancelada</span>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>{{ $tarea->fechaVencimiento ? $tarea->fechaVencimiento->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>
                                                        <!-- se tienen en cuenta cuatro estados: baja, media, alta y urgente -->
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
                                                    <td>{{ $tarea->titulo }}</td>
                                                    <td>{{ $tarea->descripcion }}</td> 
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
            <div class="col-lg-5">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Última inspección</h5>
        </div>
                <div class="ibox float-e-margins">
                    @php
                        $ultima = Auth::user()->ultimaInspeccion;
                    @endphp
                    
                    <div class="ibox-title">
                        <span class="label label-warning pull-right">colmena # {{ $ultima && $ultima->colmena ? $ultima->colmena->codigo : 'N/A' }} - {{ $ultima && $ultima->colmena ? $ultima->colmena->apiario->nombre : 'N/A' }}</span>
                        <h5>Última inspección</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Fecha inspección</small>
                                <h4> {{ Auth::user()->ultima_inspeccion_fecha ?? 'N/A' }}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Estado Colmena</small>
                                @php
                                    
                                    $estado = $ultima ? $ultima->estadoOperativo : 'N/A';

        @php
            $ultima = Auth::user()->ultimaInspeccion; // ya lo usas abajo
        @endphp

        <!-- NUEVA PRIMERA FILA: info de colmena y apiario -->
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-6">
                    <small class="stats-label">Colmena</small>
                    <h4>
                        {{ $ultima && $ultima->colmena ? $ultima->colmena->codigo : 'N/A' }}
                    </h4>
                </div>
                <div class="col-xs-6">
                    <small class="stats-label">Apiario</small>
                    <h4>
                        {{ $ultima && $ultima->colmena && $ultima->colmena->apiario ? $ultima->colmena->apiario->nombre : 'N/A' }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- FILA ORIGINAL 1 -->
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4">
                    <small class="stats-label">Fecha inspección</small>
                    <h4> {{ Auth::user()->ultima_inspeccion_fecha ?? 'N/A' }}</h4>
                </div>
                <div class="col-xs-4">
                    <small class="stats-label">Estado Colmena</small>
                    @php
                        $estado = $ultima ? $ultima->estadoOperativo : 'N/A';

                        $color = match (strtolower($estado)) {
                            'activa' => 'text-success',     // verde
                            'zanganera' => 'text-warning',   // amarillo
                            'enferma' => 'label-danger',     // rojo
                            default => 'text-muted',         // gris
                        };
                    @endphp
                    <h4 class="{{ $color }}">
                        {{ $estado }}
                    </h4>
                </div>
                <div class="col-xs-4">
                    <small class="stats-label">Temperamento</small>
                    <h4> {{ Auth::user()->ultimaInspeccion() ? Auth::user()->ultimaInspeccion->temperamento : 'N/A' }} </h4>
                </div>
            </div>
        </div>

        <!-- FILA ORIGINAL 2 -->
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4">
                    <small class="stats-label">Estado reyna</small>
                    <h4>{{ Auth::user()->ultimaInspeccion() ? Auth::user()->ultimaInspeccion->estadoReyna : 'N/A' }}</h4>
                </div>

                <div class="col-xs-4">
                    <small class="stats-label">Reserva Miel</small>
                    <h4>{{ Auth::user()->ultimaInspeccion() ? Auth::user()->ultimaInspeccion->reservaMiel : 'N/A' }}</h4>
                </div>
                <div class="col-xs-4">
                    <small class="stats-label">Reserva polen</small>
                    <h4>{{ Auth::user()->ultimaInspeccion() ? Auth::user()->ultimaInspeccion->reservaPolen : 'N/A' }}</h4>
                </div>
            </div>
        </div>

        <!-- FILA ORIGINAL 3 -->
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4">
                    <small class="stats-label">Hormigas</small>
                    @php
                        $hormigas = $ultima ? $ultima->hormigas : 'N/A';

                        if($hormigas==0){
                            $colorHormigas = 'text-success';
                        }else{
                            $colorHormigas='label-danger';
                        }
                    @endphp
                    <h4 class="{{ $colorHormigas }}">
                        {{ $ultima ? ($ultima->hormigas == 0 ? 'No' : 'Sí') : 'N/A' }}   
                    </h4>
                </div>

                <div class="col-xs-4">
                    <small class="stats-label">Varroa</small>
                    @php
                        $varroa = $ultima ? $ultima->varroa : 'N/A';

                        if($varroa==0){
                            $colorVarroa = 'text-success';
                        }else{
                            $colorVarroa='label-danger';
                        }
                    @endphp
                    <h4 class="{{ $colorVarroa }}">
                        {{ $ultima ? ($ultima->varroa == 0 ? 'No' : 'Sí') : 'N/A' }}
                    </h4>
                </div>
                <div class="col-xs-4">
                    <small class="stats-label">Loque europea</small>
                    @php
                        $loque = $ultima ? $ultima->loque_europea : 'N/A';

                        if($loque==0){
                            $colorLoque = 'text-success';
                        }else{
                            $colorLoque='label-danger';
                        }
                    @endphp
                    <h4 class="{{ $colorLoque }}">
                        {{ $ultima ? ($ultima->loque_europea == 0 ? 'No' : 'Sí') : 'N/A' }}
                    </h4>
                </div>
            </div>
        </div>

    </div>
</div>


        </div>
    @endsection
    