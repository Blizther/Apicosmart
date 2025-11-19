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
                        <label for="colmena_id">Colmena *</label>
                        <select name="idColmena" id="idColmena" class="form-control" required>
                            <option value="" disabled selected>Seleccione una colmena</option>
                            @foreach ($colmenas as $colmena)
                            <option value="{{ $colmena->idColmena }}" {{ old('idColmena') == $colmena->idColmena ? 'selected' : '' }}>
                               Colmena # {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaAdministracion">Fecha de suministración *</label>
                        <input type="date" class="form-control" id="fechaSuministracion" name="fechaSuministracion"
                            value="{{ old('fechaSuministracion') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="tipoAlimento">Alimentación Suministrada *</label>
                        <!--lista de tipos de alimentacion ('torta proteica', 'jarabe azucar', 'sustituto polen', 'agua vitaminada', 'otro')      -->
                    <select name="tipoAlimento" id="tipoAlimento" class="form-control" required>
                        <option value="" disabled {{ old('tipoAlimento') ? '' : 'selected' }}>
                            Seleccione tipo de alimentación
                        </option>
                        <option value="torta proteica" {{ old('tipoAlimento') == 'torta proteica' ? 'selected' : '' }}>Torta proteica</option>
                        <option value="jarabe azucar" {{ old('tipoAlimento') == 'jarabe azucar' ? 'selected' : '' }}>Jarabe de azúcar</option>
                        <option value="sustituto polen" {{ old('tipoAlimento') == 'sustituto polen' ? 'selected' : '' }}>Sustituto de polen</option>
                        <option value="agua vitaminada" {{ old('tipoAlimento') == 'agua vitaminada' ? 'selected' : '' }}>Agua vitaminada</option>
                        <option value="otro" {{ old('tipoAlimento') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>



                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="cantidad">Cantidad *</label>
                        <input type="number" min="0" step="0.1" class="form-control" id="cantidad"
                            placeholder="Ej. 2.00" name="cantidad" value="{{ old('cantidad') }}" required autocomplete="off">
                        
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="unidadMedida">Unidad de medida *</label>
                        <!--lista de tratamientos ('gr', 'Kg', 'ml', 'L')      -->
                        <select name="unidadMedida" id="unidadMedida" class="form-control" required>
                            <option value="" disabled {{ old('unidadMedida') ? '' : 'selected' }}>
                                Seleccione unidad de medida
                            </option>
                            <option value="gr" {{ old('unidadMedida') == 'gr' ? 'selected' : '' }}>Gramos (g)</option>
                            <option value="Kg" {{ old('unidadMedida') == 'Kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="ml" {{ old('unidadMedida') == 'ml' ? 'selected' : '' }}>Mililitros (mL)</option>
                            <option value="L" {{ old('unidadMedida') == 'L' ? 'selected' : '' }}>Litros (L)</option>
                        </select>

                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="motivo">Motivo *</label>
                        <!--lista de motivos ('estimulacion', 'reserva invernal', 'carencia nectar', 'emergencia', 'otro'))      -->
                        <select name="motivo" id="motivo" class="form-control" required>
                            <option value="" disabled {{ old('motivo') ? '' : 'selected' }}>
                                Seleccione motivo
                            </option>
                            <option value="estimulacion" {{ old('motivo') == 'estimulacion' ? 'selected' : '' }}>Estimulación</option>
                            <option value="reserva invernal" {{ old('motivo') == 'reserva invernal' ? 'selected' : '' }}>Reserva invernal</option>
                            <option value="carencia nectar" {{ old('motivo') == 'carencia nectar' ? 'selected' : '' }}>Carencia de néctar</option>
                            <option value="emergencia" {{ old('motivo') == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                            <option value="otro" {{ old('motivo') == 'otro' ? 'selected' : '' }}>Otro</option>
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
    // Mapa tipoAlimento -> unidadMedida sugerida
    const unidadPorAlimento = {
        'torta proteica': 'gr',
        'jarabe azucar': 'ml',
        'sustituto polen': 'gr',
        'agua vitaminada': 'ml',
        'otro': ''
    };

    function actualizarUnidadMedidaSegunAlimento() {
        const tipoSelect   = document.getElementById('tipoAlimento');
        const unidadSelect = document.getElementById('unidadMedida');

        if (!tipoSelect || !unidadSelect) return;

        const tipo = tipoSelect.value;
        const unidadSugerida = unidadPorAlimento[tipo] || '';

        if (unidadSugerida) {
            // Si hay una unidad configurada para ese alimento, la seleccionamos
            unidadSelect.value = unidadSugerida;
        } else {
            // Si no, dejamos la opción por defecto (sin seleccionar)
            // Solo si actualmente no hay nada elegido
            if (!unidadSelect.value) {
                unidadSelect.selectedIndex = 0; // normalmente la opción "Seleccione unidad..."
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect = document.getElementById('tipoAlimento');
        if (!tipoSelect) return;

        // Cuando cambie el tipo de alimento
        tipoSelect.addEventListener('change', actualizarUnidadMedidaSegunAlimento);

        // Por si viene con old(...) después de un error de validación
        actualizarUnidadMedidaSegunAlimento();
    });
</script>


<!-- Sale & Revenue End -->
@endsection