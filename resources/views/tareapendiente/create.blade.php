@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

    <a href="{{ route('tarea.index') }}" class="btn btn-warning mb-3">
        <i class="fa fa-arrow-left"></i> Volver a la lista
    </a>

    <div class="bg-light rounded p-4">
        <h2 class="mb-3">Registrar Nueva Tarea Pendiente</h2>
        <p class="mb-4">Complete el formulario</p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los siguientes campos:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tarea.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="idColmena" class="form-label">Colmena</label>
                    <select name="idColmena" id="idColmena" class="form-select" required>
                        <option value="" disabled selected>Seleccione una colmena</option>
                        @foreach(App\Models\Colmena::all() as $colmena)
                            <option value="{{ $colmena->idColmena }}">
                                Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo de tarea</label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="" disabled selected>Seleccione tipo</option>
                        <option value="inspeccion">Inspección</option>
                        <option value="cosecha">Cosecha</option>
                        <option value="tratamiento">Tratamiento</option>
                        <option value="alimentacion">Alimentación</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="prioridad" class="form-label">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-select" required>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="enProgreso">En progreso</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="vencida">Vencida</option>
                    </select>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" maxlength="100" required>
                </div>
                <div class="col-md-6">
                    <label for="fechaRecordatorio" class="form-label">Fecha de recordatorio</label>
                    <input
                        type="date"
                        class="form-control"
                        name="fechaRecordatorio"
                        id="fechaRecordatorio"
                        value="{{ old('fechaRecordatorio') }}"
                    >

                </div>

                
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fechaInicio" class="form-label">Fecha de inicio</label>
                    <input
                        type="date"
                        class="form-control"
                        name="fechaInicio"
                        id="fechaInicio"
                        value="{{ old('fechaInicio') }}"
                        required
                    >

                </div>

                <div class="col-md-6">
                    <label for="fechaFin" class="form-label">Fecha de fin</label>
                    <input
                        type="date"
                        class="form-control"
                        name="fechaFin"
                        id="fechaFin"
                        value="{{ old('fechaFin') }}"
                        required
                    >

                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    GUARDAR TAREA
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
