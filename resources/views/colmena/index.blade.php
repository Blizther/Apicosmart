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

    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('colmena.create')}}">
                <button type="submit" class="btn btn-success">AGREGAR COLMENA</button>
            </a>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de colmenas</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre de Apiario</th>
                            <th scope="col">Fecha Fabricación</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Cantidad de Marcos</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $correlativo=1;
                        ?>
                        @foreach ($colmenas as $colmena)
                            <tr>
                                <th scope="row"><?php echo $correlativo; ?></th>
                                <td>{{ $colmena->codigo }}</td>
                                <td>{{ $colmena->apiario->nombre }}</td>
                                <td>{{ $colmena->fechaFabricacion }}</td>
                                <td>{{ $colmena->estado }}</td>
                                <td>{{ $colmena->cantidadMarco }}</td>
                                
                                <td>
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('apiario.destroy', $colmena->idColmena) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta colmena?')">
                                            Eliminar
                                        </button>
                                    </form>

                                    <a href="{{ route('apiario.edit', $colmena->idColmena) }}" class="btn btn-warning btn-sm">Editar</a>
                                </td>
                            </tr>
                        <?php
                        $correlativo++;
                        ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

@endsection