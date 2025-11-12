@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">

    {{-- Muestra de errores igual que en "Agregar nuevo producto" --}}
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

    {{-- Botón volver --}}
    <div class="row g-4">
        <div class="col-sm-12">
            <a href="<?php echo asset(''); ?>productos">
                <button type="submit" class="btn btn-warning">VOLVER A LISTA</button>
            </a>
        </div>
    </div>

    {{-- Título y formulario --}}
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Modificar producto</h1>
            </div>
        </div>

        <form action="{{ route('productos.actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Complete el formulario</h6>

                    @php
                        $unidadSeleccionada = old('unidadMedida', $producto->unidadMedida);
                    @endphp

                    {{-- Nombre del producto --}}
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Nombre </label>
                        <input
                            type="text"
                            class="form-control"
                            id="descripcion"
                            name="descripcion"
                            placeholder="Nombre del producto"
                            value="{{ old('descripcion', $producto->descripcion) }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Unidad de medida --}}
                    <div class="mb-3">
                        <label for="unidadMedida" class="form-label">Unidad de medida </label>
                        <select
                            name="unidadMedida"
                            id="unidadMedida"
                            class="form-control"
                            required>
                            <option value="" disabled {{ $unidadSeleccionada ? '' : 'selected' }}>
                                Seleccione unidad de medida
                            </option>
                            <option value="kilogramo"   {{ $unidadSeleccionada == 'kilogramo' ? 'selected' : '' }}>Kilogramo (kg)</option>
                            <option value="gramo"       {{ $unidadSeleccionada == 'gramo' ? 'selected' : '' }}>Gramo (g)</option>
                            <option value="litro"       {{ $unidadSeleccionada == 'litro' ? 'selected' : '' }}>Litro (L)</option>
                            <option value="mililitro"   {{ $unidadSeleccionada == 'mililitro' ? 'selected' : '' }}>Mililitro (mL)</option>
                            <option value="frasco"      {{ $unidadSeleccionada == 'frasco' ? 'selected' : '' }}>Frasco</option>
                            <option value="tarro"       {{ $unidadSeleccionada == 'tarro' ? 'selected' : '' }}>Tarro</option>
                            <option value="unidad"      {{ $unidadSeleccionada == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        </select>
                    </div>

                    {{-- Stock --}}
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock </label>
                        <input
                            type="number"
                            step="1"
                            min="0"
                            max="500"
                            class="form-control"
                            id="stock"
                            name="stock"
                            placeholder="Cantidad disponible"
                            value="{{ old('stock', $producto->stock) }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Precio --}}
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio </label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="500"
                            class="form-control"
                            id="precio"
                            name="precio"
                            placeholder="Precio del producto"
                            value="{{ old('precio', $producto->precio) }}"
                            required
                            autocomplete="off">
                    </div>

                    {{-- Imagen (opcional) --}}
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Actualizar imagen</label><br>
                        @if($producto->imagen)
                            <img src="{{ asset($producto->imagen) }}" alt="Imagen Actual" width="150"><br><br>
                        @endif
                        <input type="file" name="imagen" id="imagen" class="form-control">
                    </div>

                    <div class="form-floating">
                        <button type="submit" class="btn btn-primary w-100">GUARDAR CAMBIOS</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<!-- Sale & Revenue End -->
@endsection
