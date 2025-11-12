@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
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
            <a href="<?php echo asset(''); ?>productos">
                <button type="submit" class="btn btn-warning">VOLVER A LISTA</button>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar nuevo producto</h1>
            </div>
        </div>

        <form action="<?php echo asset(''); ?>productos/guardarproducto" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Complete el formulario</h6>

                    {{-- Nombre del producto --}}
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Nombre *</label>
                        <input
                            type="text"
                            class="form-control"
                            id="descripcion"
                            name="descripcion"
                            placeholder="Nombre del producto"
                            value="{{ old('descripcion') }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Unidad de medida --}}
                    <div class="mb-3">
                        <label for="unidadMedida" class="form-label">Unidad de medida *</label>
                        <select
                            name="unidadMedida"
                            id="unidadMedida"
                            class="form-control"
                            required>
                            <option value="" disabled {{ old('unidadMedida') ? '' : 'selected' }}>
                                Seleccione unidad de medida
                            </option>
                            <option value="kilogramo"   {{ old('unidadMedida') == 'kilogramo' ? 'selected' : '' }}>Kilogramo (kg)</option>
                            <option value="gramo"       {{ old('unidadMedida') == 'gramo' ? 'selected' : '' }}>Gramo (g)</option>
                            <option value="litro"       {{ old('unidadMedida') == 'litro' ? 'selected' : '' }}>Litro (L)</option>
                            <option value="mililitro"   {{ old('unidadMedida') == 'mililitro' ? 'selected' : '' }}>Mililitro (mL)</option>
                            <option value="frasco"      {{ old('unidadMedida') == 'frasco' ? 'selected' : '' }}>Frasco</option>
                            <option value="tarro"       {{ old('unidadMedida') == 'tarro' ? 'selected' : '' }}>Tarro</option>
                            <option value="unidad"      {{ old('unidadMedida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        </select>
                    </div>

                    {{-- Stock --}}
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock *</label>
                        <input
                            type="number"
                            step="1"
                            min="0"
                            max="500"
                            class="form-control"
                            id="stock"
                            name="stock"
                            placeholder="Cantidad disponible "
                            value="{{ old('stock') }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Precio --}}
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio *</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="500"
                            class="form-control"
                            id="precio"
                            name="precio"
                            placeholder="Precio del producto"
                            value="{{ old('precio') }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Imagen (opcional) --}}
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir imagen</label>
                        <input type="file" name="imagen" id="imagen" class="form-control">
                    </div>

                    <div class="form-floating">
                        <button type="submit" class="btn btn-primary w-100">GUARDAR PRODUCTO</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<!-- Sale & Revenue End -->
@endsection
