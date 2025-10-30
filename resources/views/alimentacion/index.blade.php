@extends('usuario.inicio')
@section('content')

<!-- en esta vista deben visualizarse todas alimentaciones suministradas a las colmenas del usuario -->
<div class="row g-4">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('successdelete'))
                <div class="alert alert-success">
                    {{ session('successdelete') }}
                </div>
            @endif

            @if (session('successedit'))
                <div class="alert alert-success">
                    {{ session('successedit') }}
                </div>
            @endif
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('alimentacion.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> Agregar Alimentación
        </a>
    </div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Alimentación</h1>
            </div>
        </div>
    </div>
    <!-- Aquí va el contenido específico de la vista de Alimentación -->
    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            
            <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Colmena - Apiario</th>
                            <th scope="col">Alimento</th>
                            <th scope="col">cantidad</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        <!-- ordenar las alimentaciones por fecha de suministracion descendente y por fecha creacion-->
                         @php
                            $alimentaciones = $alimentaciones->sortByDesc(function($alimentacion) {
                                return [$alimentacion->fechaSuministracion, $alimentacion->fechaCreacion];
                            });
                        @endphp
                         

                        @foreach ($alimentaciones as $alimentacion)
                            <tr >
                                <th scope="row">{{ $correlativo }}</th>
                                <td>{{ \Carbon\Carbon::parse($alimentacion->fechaSuministracion)->format('d/m/Y') }}</td>
                                <!-- Obtener la colmena asociada al tratamiento -->
                                @php
                                    $colmena = $alimentacion->colmena;
                                @endphp
                                <td>Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}</td>
                                <td>{{ $alimentacion->tipoAlimento }}</td>
                                <td>{{ $alimentacion->cantidad }} - {{$alimentacion->unidadMedida}}</td>
                                <td>{{ $alimentacion->motivo }}</td>
                                <td>{{ $alimentacion->observaciones }}</td>
                            </tr>
                            @php $correlativo++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection