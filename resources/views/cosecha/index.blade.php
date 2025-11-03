@extends('usuario.inicio')
@section('content')

<div class="row g-4">
    <div class="col-sm-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('successdelete'))
            <div class="alert alert-success">{{ session('successdelete') }}</div>
        @endif
        @if (session('successedit'))
            <div class="alert alert-success">{{ session('successedit') }}</div>
        @endif
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('cosechas.create')}}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar Cosecha
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Cosechas</h1>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col" style="width: 5%;">NRO</th>
                            <th scope="col" style="width: 25%;">Colmena - Apiario</th>
<<<<<<< HEAD
                            <th scope="col" style="width: 10%;">Peso</th>
=======
                            <th scope="col" style="width: 10%;">Peso (Kg)</th>
>>>>>>> origin/pablo
                            <th scope="col" style="width: 20%;">Fecha Cosecha</th>
                            <th scope="col" style="width: 25%;">Observaciones</th>
                            <th scope="col" style="width: 15%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        @foreach ($cosechas as $cosecha)
                            <tr>
                                <th scope="row">{{ $correlativo }}</th>
                                @php
                                    $colmena = App\Models\Colmena::find($cosecha->idColmena);
                                @endphp
                                <td> Colmena # {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}</td>
                                <td>{{ $cosecha->peso }}</td>
                                <td>{{ \Carbon\Carbon::parse($cosecha->fechaCosecha)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $cosecha->observaciones }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('cosechas.destroy', $cosecha->idCosecha) }}" method="POST" onsubmit="return confirm('¿Desea eliminar esta cosecha?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
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
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> origin/pablo
