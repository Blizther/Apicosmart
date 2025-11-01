@extends('usuario.inicio')
@section('content')
<!--  -->
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
            <!--modelo  -->
            <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <select name="modelo" id="modelo" class="form-control" required>
                    @php
                    $modeloSeleccionado = old('modelo', $colmena->modelo);
                    @endphp

                    <option value="langstroth" {{ $modeloSeleccionado == 'langstroth' ? 'selected' : '' }}>Langstroth</option>
                    <option value="dadant" {{ $modeloSeleccionado == 'dadant' ? 'selected' : '' }}>Dadant</option>
                    <option value="topBar" {{ $modeloSeleccionado == 'topBar' ? 'selected' : '' }}>Top Bar</option>
                    <option value="warre" {{ $modeloSeleccionado == 'Warre' ? 'selected' : '' }}>Warre</option>
                </select>
            </div>
            <!--estado operativo  -->
            <div class="mb-3">
                <label for="estadoOperativo" class="form-label">Estado Operativo</label>
                <select name="estadoOperativo" id="estadoOperativo" class="form-control" required>
                    @php
                    $estadoOperativoSeleccionado = old('estadoOperativo', $colmena->estadoOperativo);
                    @endphp 
                    <option value="activa" {{ $estadoOperativoSeleccionado == 'activa' ? 'selected' : '' }}>Activa</option>
                    <option value="inactiva" {{ $estadoOperativoSeleccionado == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    <option value="enferma" {{ $estadoOperativoSeleccionado == 'enferma' ? 'selected' : '' }}>Enferma</option>
                    <option value="zanganera" {{ $estadoOperativoSeleccionado == 'zanganera' ? 'selected' : '' }}>Zanganera</option>
                    <option value="huerfana" {{ $estadoOperativoSeleccionado == 'huerfana' ? 'selected' : '' }}>Huerfana</option>
                    <option value="en_division" {{ $estadoOperativoSeleccionado == 'en_division' ? 'selected' : '' }}>En division</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

    </div>
</div>
<!-- Sale & Revenue End -->
@endsection