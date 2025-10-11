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
        <a href="{{ route('colmenas.index')}}" class="btn btn-warning">
            <i class="fa fa-arrow-left"></i> Volver a la lista
        </a>
    </div>
    </div>
    

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar nueva colmena</h1>
            </div>
        </div>

        <!--
@csrf es una directiva en Laravel que se utiliza para incluir un token de seguridad CSRF (Cross-Site Request Forgery) dentro de los formularios HTML. 
Cuando se usa la directiva @csrf dentro de un formulario Blade, Laravel genera un campo oculto (<input type="hidden">) con un token único que será verificado al recibir la solicitud en el servidor.
sin ese código el guardado no se activa 
-->

        <form action="{{ route('colmenas.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-2 row">
                    <h6 class="mb-4 col-12">Complete el formulario</h6>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="codigo">Código o Nro</label>
                        <input type="text" class="form-control" id="codigo"
                            placeholder="Código" name="codigo" value="{{ old('codigo') }}" required autocomplete="off" readonly>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaFabricacion">Fecha de Fabricación</label>
                        <input type="date" class="form-control" id="fechaFabricacion"
                            placeholder="Fecha de Fabricacion" name="fechaFabricacion" value="{{ old('fechaFabricacion') }}" required autocomplete="off">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado"  class="form-control" required>
                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>ACTIVO</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                    <label for="apiario" class="form-label">Selecciona un Apiario</label>
                    <select id="apiario" name="apiario" class="form-control" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($apiarios as $apiario)
                            <option 
                                value="{{ $apiario->idApiario }}" 
                                data-total="{{ $apiario->colmenas_count }}">
                                {{ $apiario->nombre }}
                            </option>
                        @endforeach
                    </select>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="cantidadMarco">Cantidad de Marco</label>
                        <input type="number" class="form-control" id="cantidadMarco"
                            placeholder="Cantidad de Marco" name="cantidadMarco" value="{{ old('cantidadMarco') ?? 0 }}" required autocomplete="off" min="0">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="modelo">Modelo</label>
                        <select name="modelo" id="modelo"  class="form-control" required>
                        <option value="Langstroth" {{ old('modelo') == 'langstroth' ? 'selected' : '' }}>LANGSTROTH</option>
                        <option value="Dadant" {{ old('modelo') == 'dadant' ? 'selected' : '' }}>DADANT</option>
                         <option value="Warre" {{ old('modelo') == 'Warre' ? 'selected' : '' }}>WARRE</option>
                        <option value="Warre" {{ old('modelo') == 'layens' ? 'selected' : '' }}>Layens</option>
                        <option value="Top Bar" {{ old('modelo') == 'TopBar' ? 'selected' : '' }}>TOP BAR</option>
                         <option value="Warre" {{ old('modelo') == 'flowHive' ? 'selected' : '' }}>FLOW HIVE</option>
                        <option value="Otro" {{ old('modelo') == 'otro' ? 'selected' : '' }}>OTRO</option>
                        </select>
                    </div>
                    
                    
                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">GUARDAR COLMENA</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    document.getElementById('apiario').addEventListener('change', function () {
        let total = this.options[this.selectedIndex].getAttribute('data-total');
        if (total !== null) {
            document.getElementById('codigo').value = parseInt(total) + 1;
        } else {
            document.getElementById('codigo').value = '';
        }
    });
</script>

<!-- Sale & Revenue End -->
@endsection