@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{route('colmenas.index')}}">
                <button type="submit" class="btn btn-warning">VOLVER A LISTA</button>
            </a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Modificar Colmena</h1>
            </div>
        </div>
        <form action="{{ route('colmenas.update',$colmena->idColmena) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nro o Codigo</label>
                <input type="text" name="codigo" class="form-control" value="{{$colmena->codigo }}" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Cantidad de Marcos</label>
                <input type="number" min="0" name="cantidadMarco" class="form-control" value="{{$colmena->cantidadMarco }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

    </div>
</div>
<!-- Sale & Revenue End -->
@endsection