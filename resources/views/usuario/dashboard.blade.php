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
                            0   
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
                            {{ Auth::user()->colmenasActivas->count() }}
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
                            {{ Auth::user()->colmenasActivas->count() }}
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
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>User</th>
                                                <th>Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><small>Pending...</small></td>
                                                <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                                <td>Samantha</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                            </tr>
                                            <tr>
                                                <td><span class="label label-warning">Canceled</span> </td>
                                                <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                                <td>Monica</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                            </tr>
                                            <tr>
                                                <td><small>Pending...</small> </td>
                                                <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                                <td>John</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                            </tr>
                                            <tr>
                                                <td><small>Pending...</small> </td>
                                                <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                                <td>Agnes</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                            </tr>
                                            <tr>
                                                <td><small>Pending...</small> </td>
                                                <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                                <td>Janet</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                            </tr>
                                            <tr>
                                                <td><span class="label label-primary">Completed</span> </td>
                                                <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                                <td>Amelia</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                            </tr>
                                            <tr>
                                                <td><small>Pending...</small> </td>
                                                <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                                <td>Damian</td>
                                                <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                            </tr>
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
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Fecha inspección</small>
                                <h4> {{ Auth::user()->ultima_inspeccion_fecha ?? 'N/A' }}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Estado Colmena</small>
                                @php
                                    $ultima = Auth::user()->ultimaInspeccion;
                                    $estado = $ultima ? $ultima->estadoOperativo : 'N/A';

                                    // Define colores según el estado
                                    $color = match (strtolower($estado)) {
                                        'activa' => 'text-success',     // verde
                                        'zanganera' => 'text-warning',   // amarillo
                                        'enferma' => 'label-danger',       // rojo
                                        default => 'text-muted',       // gris (N/A u otro)
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
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Hormigas</small>
                                @php
                                    $ultima = Auth::user()->ultimaInspeccion;
                                    $hormigas = $ultima ? $ultima->hormigas : 'N/A';

                                    // Define colores según el estado
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
                                    $ultima = Auth::user()->ultimaInspeccion;
                                    $varroa = $ultima ? $ultima->varroa : 'N/A';

                                    // Define colores según el estado
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
                                    $ultima = Auth::user()->ultimaInspeccion;
                                    $loque = $ultima ? $ultima->loque_europea : 'N/A';

                                    // Define colores según el estado
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
    