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
            <a href="{{ route('tratamiento.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar nuevo tratamiento</h1>
            </div>
        </div>

        <!--
@csrf es una directiva en Laravel que se utiliza para incluir un token de seguridad CSRF (Cross-Site Request Forgery) dentro de los formularios HTML. 
Cuando se usa la directiva @csrf dentro de un formulario Blade, Laravel genera un campo oculto (<input type="hidden">) con un token único que será verificado al recibir la solicitud en el servidor.
sin ese código el guardado no se activa 
-->

        <form action="{{ route('tratamiento.store')}}" method="POST" enctype="multipart/form-data">
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
                                {{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="fechaAdministracion">Fecha de administración *</label>
                        <input type="date" class="form-control" id="fechaAdministracion"
                            placeholder="Fecha de administración" name="fechaAdministracion" value="{{ old('fechaAdministracion') }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required autocomplete="off">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="estado">Problema tratado *</label>
                        <select name="problemaTratado" id="problemaTratado" class="form-control" required>
                            <option value="" disabled selected>Seleccione un problema</option>
                            <option value="varroa" {{ old('problemaTratado') == 'varroa' ? 'selected' : '' }}>VARROA</option>
                            <option value="loque" {{ old('problemaTratado') == 'loque' ? 'selected' : '' }}>LOQUE</option>
                            <option value="ascosferosis" {{ old('problemaTratado') == 'ascosferosis' ? 'selected' : '' }}>ASCOSFEROSIS</option>
                            <option value="otra" {{ old('problemaTratado') == 'otra' ? 'selected' : '' }}>OTRA</option>
                        </select>
                    </div>


                    <div class="mb-3 col-12 col-md-6">
                        <label for="tratamientoAdministrado">Tratamiento administrado *</label>
                        <select name="tratamientoAdministrado" id="tratamientoAdministrado" class="form-control" required>
                            <option value="" disabled selected>Seleccione un tratamiento</option>
                            <option value="amitraz" {{ old('tratamientoAdministrado') == 'amitraz' ? 'selected' : '' }}>AMITRAZ</option>
                            <option value="fluvalinato" {{ old('tratamientoAdministrado') == 'fluvalinato' ? 'selected' : '' }}>FLUVALINATO</option>
                            <option value="oxalico" {{ old('tratamientoAdministrado') == 'oxalico' ? 'selected' : '' }}>OXALICO</option>
                            <option value="formico" {{ old('tratamientoAdministrado') == 'formico' ? 'selected' : '' }}>FORMICO</option>
                            <option value="tiamina" {{ old('tratamientoAdministrado') == 'tiamina' ? 'selected' : '' }}>TIAMINA</option>
                            <option value="otro" {{ old('tratamientoAdministrado') == 'otro' ? 'selected' : '' }}>OTRO</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label for="modelo">Descripcion (opcional)</label>
                        <!--descripcion del tratamiento aplicado en una caja de texto grande-->
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>

                    </div>


                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">GUARDAR TRATAMIENTO</button>
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