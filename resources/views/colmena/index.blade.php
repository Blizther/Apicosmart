@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">

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
        <a href="{{ route('colmenas.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> Agregar colmena
        </a>
        <a href="{{ route('colmenas.createLote')}}" class="btn btn-primary ms-2">
            <i class="fa fa-layer-group"></i> Agregar colmenas por lote
        </a>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de colmenas</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre de Apiario</th>
                            <th scope="col">Fecha Fabricación</th>
                            <th scope="col">Estado Operativo</th>
                            <th scope="col">Cantidad de Marcos</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        @foreach ($colmenas as $colmena)
                        <tr class="{{ strtolower($colmena->estadoOperativo) == 'enferma' ? 'table-danger' : '' }}">
                            <th scope="row">{{ $correlativo }}</th>
                            <td>{{ $colmena->codigo }}</td>
                            <td>{{ $colmena->apiario->nombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($colmena->fechaFabricacion)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ ucfirst($colmena->estadoOperativo) }}</td>
                            <td>{{ $colmena->cantidadMarco }}</td>
                            <td>{{ $colmena->modelo }}</td>
                            <td>
                                <a href="{{ route('colmenas.verinspeccion', $colmena->idColmena) }}" class="btn btn-primary btn-sm">
                                    Ver Inspecciones
                                </a>
                                <a href="{{ route('colmenas.show', $colmena->idColmena) }}" class="btn btn-info btn-sm">
                                    Detalles
                                </a>
                                <a href="{{ route('colmenas.edit', $colmena->idColmena) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                <form action="{{ route('colmenas.destroy', $colmena->idColmena) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta colmena?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $correlativo++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .table-danger {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }
</style>

@endsection