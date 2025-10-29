@extends('usuario.inicio')
@section('content')
<!--se debe mostrar la colmena seleccionada, incluyendo su apiario, la lista de las inspecciones realizadas y los tratamientos-->
<div class="container-fluid pt-4 px-4">

    <div class="row g-4">
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
                <p><strong>Fecha de Fabricación:</strong> {{ $colmena->fechaFabricacion }}</p>
                <p><strong>Estado Operativo:</strong> {{ $colmena->estadoOperativo }}</p>
                <p><strong>Cantidad de Marcos:</strong> {{ $colmena->cantidadMarcos }}</p>
                <p><strong>Modelo:</strong> {{ $colmena->modelo }}</p>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="bg-light rounded h-100 p-4">
                <h3>Inspecciones Realizadas</h3>
                @if($colmena->inspecciones->isEmpty())
                    <p>No se han realizado inspecciones en esta colmena.</p>
                @else
                    <ul>
                        @foreach($colmena->inspecciones as $inspeccion)
                            <li>{{ $inspeccion->fechaInspeccion }} - {{ $inspeccion->observaciones }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-sm-12">
            <div class="bg-light rounded h-100 p-4">
                <h3>Tratamientos Aplicados</h3>
                @if($colmena->tratamientos->isEmpty())
                    <p>No se han aplicado tratamientos en esta colmena.</p>
                @else
                    <ul>
                        @foreach($colmena->tratamientos as $tratamiento)
                            <li>{{ $tratamiento->fechaAdministracion }} - {{ $tratamiento->tratamientoAdministrado }} ({{ $tratamiento->problemaTratado }}) - {{ $tratamiento->descripcion }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
