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
            <a href="{{ route('alimentacion.index') }}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Editar alimentación</h1>
            </div>
        </div>

        <form action="{{ route('alimentacion.update', $alimentacion->idalimentacion) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-2 row">
                    <h6 class="mb-4 col-12">Modifique los datos necesarios</h6>

                    {{-- Colmena --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="idColmena">Colmena *</label>
                        <select name="idColmena" id="idColmena" class="form-control" required>
                            @foreach ($colmenas as $colmena)
                                <option value="{{ $colmena->idColmena }}"
                                    {{ old('idColmena', $alimentacion->idColmena) == $colmena->idColmena ? 'selected' : '' }}>
                                    Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaSuministracion">Fecha de suministración *</label>
                        <input
                            type="date"
                            class="form-control"
                            id="fechaSuministracion"
                            name="fechaSuministracion"
                            value="{{ old('fechaSuministracion', \Carbon\Carbon::parse($alimentacion->fechaSuministracion)->format('Y-m-d')) }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                            required
                        >
                    </div>

                    {{-- Tipo de alimento --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="tipoAlimento">Alimentación Suministrada *</label>
                        <select name="tipoAlimento" id="tipoAlimento" class="form-control" required>
                            <option value="" disabled {{ old('tipoAlimento', $alimentacion->tipoAlimento) ? '' : 'selected' }}>
                                Seleccione tipo de alimentación
                            </option>
                            <option value="torta proteica" {{ old('tipoAlimento', $alimentacion->tipoAlimento) == 'torta proteica' ? 'selected' : '' }}>Torta proteica</option>
                            <option value="jarabe azucar" {{ old('tipoAlimento', $alimentacion->tipoAlimento) == 'jarabe azucar' ? 'selected' : '' }}>Jarabe de azúcar</option>
                            <option value="sustituto polen" {{ old('tipoAlimento', $alimentacion->tipoAlimento) == 'sustituto polen' ? 'selected' : '' }}>Sustituto de polen</option>
                            <option value="agua vitaminada" {{ old('tipoAlimento', $alimentacion->tipoAlimento) == 'agua vitaminada' ? 'selected' : '' }}>Agua vitaminada</option>
                            <option value="otro" {{ old('tipoAlimento', $alimentacion->tipoAlimento) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    {{-- Cantidad --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="cantidad">Cantidad *</label>
                        <input
                            type="number"
                            min="0"
                            step="0.1"
                            class="form-control"
                            id="cantidad"
                            placeholder="Ej. 2.00"
                            name="cantidad"
                            value="{{ old('cantidad', $alimentacion->cantidad) }}"
                            required
                            autocomplete="off"
                        >
                    </div>

                    {{-- Unidad de medida --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="unidadMedida">Unidad de medida *</label>
                        <select name="unidadMedida" id="unidadMedida" class="form-control" required>
                            <option value="" disabled {{ old('unidadMedida', $alimentacion->unidadMedida) ? '' : 'selected' }}>
                                Seleccione unidad de medida
                            </option>
                            <option value="gr" {{ old('unidadMedida', $alimentacion->unidadMedida) == 'gr' ? 'selected' : '' }}>Gramos (g)</option>
                            <option value="Kg" {{ old('unidadMedida', $alimentacion->unidadMedida) == 'Kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="ml" {{ old('unidadMedida', $alimentacion->unidadMedida) == 'ml' ? 'selected' : '' }}>Mililitros (mL)</option>
                            <option value="L"  {{ old('unidadMedida', $alimentacion->unidadMedida) == 'L' ? 'selected' : '' }}>Litros (L)</option>
                        </select>
                    </div>

                    {{-- Motivo --}}
                    <div class="mb-3 col-12 col-md-6">
                        <label for="motivo">Motivo *</label>
                        <select name="motivo" id="motivo" class="form-control" required>
                            <option value="" disabled {{ old('motivo', $alimentacion->motivo) ? '' : 'selected' }}>
                                Seleccione motivo
                            </option>
                            <option value="estimulacion" {{ old('motivo', $alimentacion->motivo) == 'estimulacion' ? 'selected' : '' }}>Estimulación</option>
                            <option value="reserva invernal" {{ old('motivo', $alimentacion->motivo) == 'reserva invernal' ? 'selected' : '' }}>Reserva invernal</option>
                            <option value="carencia nectar" {{ old('motivo', $alimentacion->motivo) == 'carencia nectar' ? 'selected' : '' }}>Carencia de néctar</option>
                            <option value="emergencia" {{ old('motivo', $alimentacion->motivo) == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                            <option value="otro" {{ old('motivo', $alimentacion->motivo) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    {{-- Observaciones --}}
                    <div class="mb-3 col-12 col-md-12">
                        <label for="observaciones">Descripción</label>
                        <textarea
                            name="observaciones"
                            id="observaciones"
                            class="form-control"
                            rows="4"
                        >{{ old('observaciones', $alimentacion->observaciones) }}</textarea>
                    </div>

                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">
                            ACTUALIZAR ALIMENTACIÓN
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
