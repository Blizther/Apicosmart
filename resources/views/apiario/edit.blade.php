@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('apiario.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i>
                VOLVER A LISTA
            </a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Modificar apiario</h1>
            </div>
        </div>
        <form action="{{ route('apiario.update',$apiario->idApiario) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{$apiario->nombre }}" required>
            </div>
            <div class="mb-3">
                <label for="vegetacion" class="form-label">Vegetación</label>
                <input type="text" name="vegetacion" class="form-control" value="{{$apiario->vegetacion }}" required>
            </div>
            <div class="mb-3">
                <label for="altitud" class="form-label">Altitud</label>
                <input type="text" name="altitud" class="form-control" value="{{$apiario->altitud }}" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="activo" {{ $apiario->estado == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ $apiario->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

    </div>
</div>
<!-- Sale & Revenue End -->
@endsection