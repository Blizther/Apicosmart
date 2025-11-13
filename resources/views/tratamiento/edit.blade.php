@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

    <div class="row g-4">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error:</strong> Corrige los siguientes campos:<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('tratamiento.index') }}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Editar tratamiento</h1>
            </div>
        </div>

        <form action="{{ route('tratamiento.update', $tratamiento->idTratamiento) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-2 row">
                    <h6 class="mb-4 col-12">Modifique los datos necesarios</h6>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="idColmena">Colmena *</label>
                        <select name="idColmena" id="idColmena" class="form-control" required>
                            @foreach ($colmenas as $colmena)
                                <option value="{{ $colmena->idColmena }}"
                                    {{ old('idColmena', $tratamiento->idColmena) == $colmena->idColmena ? 'selected' : '' }}>
                                    Colmena # {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaAdministracion">Fecha de administración *</label>
                        <input type="date"
                               class="form-control"
                               id="fechaAdministracion"
                               name="fechaAdministracion"
                               value="{{ old('fechaAdministracion', \Carbon\Carbon::parse($tratamiento->fechaAdministracion)->format('Y-m-d')) }}"
                               max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               required>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="problemaTratado">Problema tratado *</label>
                        <select name="problemaTratado" id="problemaTratado" class="form-control" required>
                            <option value="" disabled {{ old('problemaTratado', $tratamiento->problemaTratado) ? '' : 'selected' }}>
                                Seleccione un problema
                            </option>
                            <option value="varroa"       {{ old('problemaTratado', $tratamiento->problemaTratado) == 'varroa' ? 'selected' : '' }}>VARROA</option>
                            <option value="loque"        {{ old('problemaTratado', $tratamiento->problemaTratado) == 'loque' ? 'selected' : '' }}>LOQUE</option>
                            <option value="ascosferosis" {{ old('problemaTratado', $tratamiento->problemaTratado) == 'ascosferosis' ? 'selected' : '' }}>ASCOSFEROSIS</option>
                            <option value="otra"         {{ old('problemaTratado', $tratamiento->problemaTratado) == 'otra' ? 'selected' : '' }}>OTRA</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="tratamientoAdministrado">Tratamiento administrado *</label>
                        <select name="tratamientoAdministrado" id="tratamientoAdministrado" class="form-control" required>
                            <option value="" disabled {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) ? '' : 'selected' }}>
                                Seleccione un tratamiento
                            </option>
                            <option value="amitraz"       {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'amitraz' ? 'selected' : '' }}>AMITRAZ</option>
                            <option value="fluvalinato"   {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'fluvalinato' ? 'selected' : '' }}>FLUVALINATO</option>
                            <option value="oxalico"       {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'oxalico' ? 'selected' : '' }}>OXÁLICO</option>
                            <option value="formico"       {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'formico' ? 'selected' : '' }}>FÓRMICO</option>
                            <option value="tiamina"       {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'tiamina' ? 'selected' : '' }}>TIAMINA</option>
                            <option value="otro"          {{ old('tratamientoAdministrado', $tratamiento->tratamientoAdministrado) == 'otro' ? 'selected' : '' }}>OTRO</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label for="descripcion">Descripción (opcional)</label>
                        <textarea name="descripcion"
                                  id="descripcion"
                                  class="form-control"
                                  rows="4">{{ old('descripcion', $tratamiento->descripcion) }}</textarea>
                    </div>

                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">
                            ACTUALIZAR TRATAMIENTO
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
