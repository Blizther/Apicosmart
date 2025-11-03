@extends('usuario.inicio')
@section('content')

<!-- en esta vista deben visualizarse todos los tratamientos realizados a las colmenas del usuario -->
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
        <a href="{{ route('tratamiento.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> Agregar tratamiento
        </a>
    </div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Tratamientos</h1>
            </div>
        </div>
    </div>
    <!-- Aquí va el contenido específico de la vista de tratamientos -->
    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            
            <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Colmena - Apiario</th>
                            <th scope="col">Plaga/enfermedad</th>
                            <th scope="col">Tratamiento aplicado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        <!-- ordenar los tratamientos por fecha de administración descendente, si hay dos con la misma fecha, ordenar por fechaCreacion -->
                         @php
                            $tratamientos = $tratamientos->sortByDesc(function($tratamiento) {
                                return [$tratamiento->fechaAdministracion, $tratamiento->fechaCreacion];
                            });
                        @endphp
                        
                        @foreach ($tratamientos as $tratamiento)
                            <tr >
                                <th scope="row">{{ $correlativo }}</th>
                                <!-- Obtener la colmena asociada al tratamiento -->
                                @php
                                    $colmena = $tratamiento->colmena;
                                @endphp
                                <td>Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}</td>
                                <td>{{ $tratamiento->problemaTratado }}</td>
                                <td>{{ $tratamiento->tratamientoAdministrado }}</td>
                                <td>{{ \Carbon\Carbon::parse($tratamiento->fechaAdministracion)->format('d/m/Y') }}</td>
                                <td>{{ $tratamiento->descripcion }}</td>
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