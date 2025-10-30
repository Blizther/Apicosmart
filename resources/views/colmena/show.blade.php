@extends('usuario.inicio')
@section('content')
<!--se debe mostrar la colmena seleccionada, incluyendo su apiario, la lista de las inspecciones realizadas y los tratamientos-->
<div class="container-fluid pt-4 px-4">

    <div class="row g-4">
        <div class="row g-4">
            <div class="col-sm-12">
                <a href="{{route('colmenas.index')}}">
                    <button type="submit" class="btn btn-warning">
                        
                <i class="fa fa-arrow-left"></i>
                
                    VOLVER A LISTA

                    </button>
                </a>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Detalles de la Colmena: {{ $colmena->codigo }}</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="bg-light rounded h-100 p-4">
                <h3>Información de la Colmena</h3>
                <p><strong>Código:</strong> {{ $colmena->codigo }}</p>
                <p><strong>Apiario:</strong> {{ $colmena->apiario->nombre }}</p>
                <p><strong>Fecha de Fabricación:</strong> {{ \Carbon\Carbon::parse($colmena->fechaInstalacionFisica)->format('d/m/Y H:i:s') }}</p>
                <p><strong>Estado Operativo:</strong> {{ $colmena->estadoOperativo }}</p>
                <p><strong>Cantidad de Marcos:</strong> {{ $colmena->cantidadMarco }}</p>
                <p><strong>Modelo:</strong> {{ $colmena->modelo }}</p>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="bg-light rounded h-100 p-4">
                <h3>Inspecciones Realizadas</h3>
                @if($colmena->inspecciones->isEmpty())
                <p>No se han realizado inspecciones en esta colmena.</p>
                @else
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Inspecciones</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Temperamento</th>
                                        <th>Reyna</th>
                                        <th>Miel</th>
                                        <th>Polen</th>
                                        <th>Enfermedad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorre las últimas 10 inspecciones de la colmena -->
                                    @foreach($colmena->inspecciones->take(10) as $inspeccion)
                                    <tr>
                                        <td>{{ $inspeccion->fechaInspeccion }}</td>
                                        <td>{{ $inspeccion->temperamento }}</td>
                                        <td>{{ $inspeccion->estadoReyna }}</td>
                                        <td>{{ $inspeccion->reservaMiel }}</td>
                                        <td>{{ $inspeccion->reservaPolen }}</td>
                                        <td>{{ $inspeccion->enfermedadPlaga }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-sm-6">
            <div class="bg-light rounded h-100 p-4">
                <h3>Tratamientos Aplicados</h3>
                @if($colmena->tratamientos->isEmpty())
                <p>No se han aplicado tratamientos en esta colmena.</p>
                @else
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Tratamientos</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Problema</th>
                                        <th>Tratamiento</th>
                                        <th>descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorre los últimos 10 tratamientos de la colmena -->
                                    @foreach($colmena->tratamientos->take(10) as $tratamiento)
                                    <tr>
                                        <td>{{ $tratamiento->fechaAdministracion }}</td>
                                        <td>{{ $tratamiento->problemaTratado }}</td>
                                        <td>{{ $tratamiento->tratamientoAdministrado }}</td>
                                        <td>{{ $tratamiento->descripcion }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-sm-6">
            <div class="bg-light rounded h-100 p-4">
                <h3>Alimentación suministrada</h3>
                @if($colmena->alimentaciones->isEmpty())
                <p>No se han suministrado alimentaciones en esta colmena.</p>
                @else
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Alimentaciones</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Alimento</th>
                                        <th>Cantidad</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorre las últimas 10 alimentaciones de la colmena -->
                                    @foreach($colmena->alimentaciones->take(10) as $alimentacion)
                                    <tr>
                                        <td>{{ $alimentacion->fechaSuministracion }}</td>
                                        <td>{{ $alimentacion->tipoAlimento }}</td>
                                        <td>{{ $alimentacion->cantidad }} - {{ $alimentacion->unidad }}</td>
                                        <td>{{ $alimentacion->motivo }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection