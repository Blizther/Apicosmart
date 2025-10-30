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
            <a href="{{ route('alimentacion.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar nueva alimentación</h1>
            </div>
        </div>

        <!--
@csrf es una directiva en Laravel que se utiliza para incluir un token de seguridad CSRF (Cross-Site Request Forgery) dentro de los formularios HTML. 
Cuando se usa la directiva @csrf dentro de un formulario Blade, Laravel genera un campo oculto (<input type="hidden">) con un token único que será verificado al recibir la solicitud en el servidor.
sin ese código el guardado no se activa 
-->

        <form action="{{ route('alimentacion.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-2 row">
                    <h6 class="mb-4 col-12">Complete el formulario</h6>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="colmena_id">Colmena</label>
                        <select name="idColmena" id="idColmena" class="form-control" required>
                            <option value="" disabled selected>Seleccione una colmena</option>
                            @foreach ($colmenas as $colmena)
                            <option value="{{ $colmena->idColmena }}" {{ old('idColmena') == $colmena->idColmena ? 'selected' : '' }}>
                                {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaAdministracion">Fecha de suministración </label>
                        <input type="date" class="form-control" id="fechaSuministracion" name="fechaSuministracion"
                            value="{{ old('fechaSuministracion') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="tipoAlimento">Alimentación Suministrada</label>
                        <!--lista de tipos de alimentacion ('torta proteica', 'jarabe azucar', 'sustituto polen', 'agua vitaminada', 'otro')      -->
                        <select name="tipoAlimento" id="tipoAlimento" class="form-control" required>
                            <option value="torta proteica" {{ old('tipoAlimento') == 'torta proteica' ? 'selected' : '' }}>torta proteica</option>
                            <option value="jarabe azucar" {{ old('tipoAlimento') == 'jarabe azucar' ? 'selected' : '' }}>jarabe azucar</option>
                            <option value="sustituto polen" {{ old('tipoAlimento') == 'sustituto polen' ? 'selected' : '' }}>sustituto polen</option>
                            <option value="agua vitaminada" {{ old('tipoAlimento') == 'agua vitaminada' ? 'selected' : '' }}>agua vitaminada</option>
                            <option value="otro" {{ old('tipoAlimento') == 'otro' ? 'selected' : '' }}>OTRA</option>
                        </select>

                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="cantidad">cantidad</label>
                        <input type="number" min="0" step="0.1" class="form-control" id="cantidad"
                            placeholder="cantidad suministrada" name="cantidad" value="{{ old('cantidad') }}" required autocomplete="off">
                        
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="unidadMedida">unidad de medida</label>
                        <!--lista de tratamientos ('gr', 'Kg', 'ml', 'L')      -->
                        <select name="unidadMedida" id="unidadMedida" class="form-control" required>
                            <option value="gr" {{ old('unidadMedida') == 'gr' ? 'selected' : '' }}>gr</option>
                            <option value="Kg" {{ old('unidadMedida') ==    'Kg' ? 'selected' : '' }}>Kg</option>
                            <option value="ml" {{ old('unidadMedida') == 'ml' ? 'selected' : '' }}>ml</option>
                            <option value="L" {{ old('unidadMedida') == 'L' ? 'selected' : '' }}>L</option>
                        </select>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="motivo">Motivo</label>
                        <!--lista de motivos ('estimulacion', 'reserva invernal', 'carencia nectar', 'emergencia', 'otro'))      -->
                        <select name="motivo" id="motivo" class="form-control" required>
                            <option value="estimulacion" {{ old('motivo') == 'estimulacion' ? 'selected' : '' }}>Estimulacion</option>
                            <option value="reserva invernal" {{ old('motivo') ==    'reserva invernal' ? 'selected' : '' }}>Reserva invernal</option>
                            <option value="carencia nectar" {{ old('motivo') == 'carencia nectar' ? 'selected' : '' }}>Carencia de nectar</option>
                            <option value="emergencia" {{ old('motivo') == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                        </select>
                    </div>
                    <div class="mb-3 col-12 col-md-12">
                        <label for="observaciones">Descripción</label>
                        <!--descripcion del tratamiento aplicado en una caja de texto grande-->
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="4" >{{ old('observaciones') }}</textarea>

                    </div>
                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">GUARDAR ALIMENTACIÓN</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    document.getElementById('apiario').addEventListener('change', function() {
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