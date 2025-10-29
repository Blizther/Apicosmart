@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

    {{-- Errores de validación --}}
    <div class="row g-4">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error:</strong> Corrige los siguientes campos:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-2">
        <div class="col-sm-12">
            <a href="{{ route('cosechas.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h3 class="mb-0">Agregar nueva cosecha</h3>
            </div>
        </div>

        <form action="{{ route('cosechas.store') }}" method="POST" class="col-sm-12">
            @csrf
            <div class="bg-light rounded h-100 p-3 row">

                <h6 class="mb-3 col-12">Complete el formulario</h6>

                {{-- Colmena --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="idColmena" class="form-label">Colmena</label>
                    <select name="idColmena" id="idColmena" class="form-control" required>
                            <option value="" disabled selected>Seleccione una colmena</option>
                            @foreach ($colmenas as $colmena)
                                <option value="{{ $colmena->idColmena }}" {{ old('idColmena') == $colmena->idColmena ? 'selected' : '' }}>
                                    Colmena # {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                                </option>
                            @endforeach
                        </select>
                </div>

                {{-- Peso --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="peso" class="form-label">Peso (kg)</label>
                    <input type="number" step="0.01" min="0"
                           class="form-control" id="peso" name="peso"
                           value="{{ old('peso') }}" placeholder="Ej. 2.00" required>
                </div>

                {{-- Estado de la miel --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="estadoMiel" class="form-label">Estado de la miel</label>
                    <select id="estadoMiel" name="estadoMiel" class="form-control" required>
                        <option value="" disabled {{ old('estadoMiel') ? '' : 'selected' }}>Seleccione una opción</option>
                        <option value="Líquida"       {{ old('estadoMiel') === 'Líquida' ? 'selected' : '' }}>Líquida</option>
                        <option value="Cristalizada"  {{ old('estadoMiel') === 'Cristalizada' ? 'selected' : '' }}>Cristalizada</option>
                        <option value="Impura"        {{ old('estadoMiel') === 'Impura' ? 'selected' : '' }}>Madura</option>
                        <option value="Operculada"    {{ old('estadoMiel') === 'Operculada' ? 'selected' : '' }}>Operculada</option>
                    </select>
                </div>

                s{{-- Fecha de cosecha --}}
            <div class="mb-3 col-12 col-md-6">
                <label for="fechaCosecha" class="form-label">Fecha de cosecha</label>
                <input
                    type="date"
                    class="form-control"
                    id="fechaCosecha"
                    name="fechaCosecha"
                    value="{{ old('fechaCosecha') }}"
                    max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                    required
                >
            </div>


                {{-- Observaciones --}}
                <div class="mb-3 col-12">
                    <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                    <textarea class="form-control" id="observaciones" name="observaciones"
                              rows="3" maxlength="255"
                              placeholder="Notas generales...">{{ old('observaciones') }}</textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        Guardar cosecha
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
