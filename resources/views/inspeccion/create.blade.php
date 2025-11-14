@extends('usuario.inicio')

@section('content')
<div class="container-fluid pt-4 px-4">

    {{-- BOTÓN VOLVER --}}
    <div class="row g-4 mb-2">
        <div class="col-sm-12">
            <a href="{{ route('inspeccion.index', $id) }}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i>
                VOLVER A LA LISTA DE INSPECCIONES
            </a>
        </div>
    </div>

    {{-- TÍTULO --}}
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 mb-3">
                <div>
                    <h1 class="mb-1">Agregar nueva inspección</h1>
                    <small>Complete el formulario</small>
                </div>
                <h3 class="mb-0">
                    Colmena: {{ $codigoColmena }} - Apiario: {{ $nombreApiario }}
                </h3>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <form action="{{ route('inspeccion.store', $id) }}"
              method="POST"
              class="col-12">
            @csrf

            <div class="bg-light rounded h-100 p-3 row">
                {{-- FECHA DE INSPECCIÓN --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="fechaInspeccion">Fecha de inspección *</label>
                    @php
                        $hoy = date('Y-m-d');
                        $fechaOld = old('fechaInspeccion', $hoy);
                    @endphp
                    <input
                        type="date"
                        name="fechaInspeccion"
                        id="fechaInspeccion"
                        class="form-control"
                        max="{{ $hoy }}"
                        value="{{ $fechaOld }}"
                        required
                    >
                </div>

                {{-- =========================
                     ESTADO OPERATIVO
                ========================== --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="estadoOperativo">Estado operativo *</label>
                    @php $estadoOld = old('estadoOperativo', 'activa'); @endphp
                    <select name="estadoOperativo" id="estadoOperativo" class="form-control" required>
                        <option value="activa"    {{ $estadoOld == 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva"  {{ $estadoOld == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                        <option value="zanganera" {{ $estadoOld == 'zanganera' ? 'selected' : '' }}>Zanganera</option>
                        <option value="huerfana"  {{ $estadoOld == 'huerfana' ? 'selected' : '' }}>Huérfana</option>
                        <option value="enferma"   {{ $estadoOld == 'enferma' ? 'selected' : '' }}>Enferma</option>
                    </select>
                </div>

                {{-- TEMPERAMENTO --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="temperamento">Temperamento *</label>
                    @php $tempOld = old('temperamento'); @endphp
                    <select name="temperamento" id="temperamento" class="form-control" required>
                        <option value="" disabled {{ $tempOld ? '' : 'selected' }}>Seleccione el temperamento</option>
                        <option value="muy_tranquila" {{ $tempOld == 'muy_tranquila' ? 'selected' : '' }}>Muy tranquila</option>
                        <option value="tranquila"     {{ $tempOld == 'tranquila' ? 'selected' : '' }}>Tranquila</option>
                        <option value="mediana"       {{ $tempOld == 'mediana' ? 'selected' : '' }}>Mediana</option>
                        <option value="defensiva"     {{ $tempOld == 'defensiva' ? 'selected' : '' }}>Defensiva</option>
                        <option value="muy_defensiva" {{ $tempOld == 'muy_defensiva' ? 'selected' : '' }}>Muy defensiva</option>
                    </select>
                </div>

                {{-- INTENSIDAD DE IMPORTACIÓN --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="intensidadImportacion">Intensidad de importación *</label>
                    @php $intOld = old('intensidadImportacion'); @endphp
                    <select name="intensidadImportacion" id="intensidadImportacion" class="form-control" required>
                        <option value="" disabled {{ $intOld ? '' : 'selected' }}>Seleccione la intensidad de importación</option>
                        <option value="muy_baja" {{ $intOld == 'muy_baja' ? 'selected' : '' }}>Muy baja</option>
                        <option value="baja"     {{ $intOld == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media"    {{ $intOld == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta"     {{ $intOld == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="muy_alta" {{ $intOld == 'muy_alta' ? 'selected' : '' }}>Muy alta</option>
                    </select>
                </div>

                {{-- ESTADO DE LA REINA --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="estadoReina">Estado de la reina *</label>
                    @php $reinaOld = old('estadoReina'); @endphp
                    <select name="estadoReina" id="estadoReina" class="form-control" required>
                        <option value="" disabled {{ $reinaOld ? '' : 'selected' }}>Seleccione el estado de la reina</option>
                        <option value="activa_buena_postura" {{ $reinaOld == 'activa_buena_postura' ? 'selected' : '' }}>Activa / Buena postura</option>
                        <option value="postura_reducida"      {{ $reinaOld == 'postura_reducida' ? 'selected' : '' }}>Postura reducida</option>
                        <option value="vieja"                 {{ $reinaOld == 'vieja' ? 'selected' : '' }}>Vieja</option>
                        <option value="no_vista"              {{ $reinaOld == 'no_vista' ? 'selected' : '' }}>No vista</option>
                        <option value="ausente"               {{ $reinaOld == 'ausente' ? 'selected' : '' }}>Ausente</option>
                    </select>
                </div>

                {{-- RESERVA DE MIEL --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="reservaMiel">Reserva de miel *</label>
                    @php $mielOld = old('reservaMiel'); @endphp
                    <select name="reservaMiel" id="reservaMiel" class="form-control" required>
                        <option value="" disabled {{ $mielOld ? '' : 'selected' }}>Seleccione la reserva de miel</option>
                        <option value="sin_reservas"         {{ $mielOld == 'sin_reservas' ? 'selected' : '' }}>Sin reservas</option>
                        <option value="baja"                 {{ $mielOld == 'baja' ? 'selected' : '' }}>Baja / Poca</option>
                        <option value="media"                {{ $mielOld == 'media' ? 'selected' : '' }}>Media / Moderada</option>
                        <option value="alta_muchas_reservas" {{ $mielOld == 'alta_muchas_reservas' ? 'selected' : '' }}>Alta / Muchas reservas</option>
                    </select>
                </div>

                {{-- RESERVA DE POLEN --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="reservaPolen">Reserva de polen *</label>
                    @php $polenOld = old('reservaPolen'); @endphp
                    <select name="reservaPolen" id="reservaPolen" class="form-control" required>
                        <option value="" disabled {{ $polenOld ? '' : 'selected' }}>Seleccione la reserva de polen</option>
                        <option value="sin_reservas"       {{ $polenOld == 'sin_reservas' ? 'selected' : '' }}>Sin reservas</option>
                        <option value="baja"               {{ $polenOld == 'baja' ? 'selected' : '' }}>Baja / Poca</option>
                        <option value="media"              {{ $polenOld == 'media' ? 'selected' : '' }}>Media / Moderada</option>
                        <option value="alta_mucho_polen"   {{ $polenOld == 'alta_mucho_polen' ? 'selected' : '' }}>Alta / Mucho polen</option>
                    </select>
                </div>

                {{-- CELDAS REALES --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="celdasReales">Celdas reales *</label>
                    @php $celdasOld = old('celdasReales'); @endphp
                    <select name="celdasReales" id="celdasReales" class="form-control" required>
                        <option value="" disabled {{ $celdasOld ? '' : 'selected' }}>Seleccione la condición de celdas reales</option>
                        <option value="no_hay"           {{ $celdasOld == 'no_hay' ? 'selected' : '' }}>No hay</option>
                        <option value="en_progresion"    {{ $celdasOld == 'en_progresion' ? 'selected' : '' }}>En progresión (con huevo / desarrollo)</option>
                        <option value="tapadas"          {{ $celdasOld == 'tapadas' ? 'selected' : '' }}>Tapadas (operculadas)</option>
                        <option value="destruidas"       {{ $celdasOld == 'destruidas' ? 'selected' : '' }}>Destruidas / incompletas</option>
                        <option value="varias_enjambron" {{ $celdasOld == 'varias_enjambron' ? 'selected' : '' }}>Varias (abundantes, enjambrazón)</option>
                    </select>
                </div>

                {{-- PATRÓN DE POSTURA --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="patronPostura">Patrón de postura *</label>
                    @php $patronOld = old('patronPostura'); @endphp
                    <select name="patronPostura" id="patronPostura" class="form-control" required>
                        <option value="" disabled {{ $patronOld ? '' : 'selected' }}>Seleccione el patrón de postura</option>
                        <option value="patron_cerrado"         {{ $patronOld == 'patron_cerrado' ? 'selected' : '' }}>Patrón cerrado</option>
                        <option value="larvas_amarillas"       {{ $patronOld == 'larvas_amarillas' ? 'selected' : '' }}>Larvas amarillas</option>
                        <option value="huecos_en_marco"        {{ $patronOld == 'huecos_en_marco' ? 'selected' : '' }}>Huecos en un marco</option>
                        <option value="cria_zangano_abundante" {{ $patronOld == 'cria_zangano_abundante' ? 'selected' : '' }}>Cría de zángano abundante</option>
                        <option value="mucha_cria_operculada"  {{ $patronOld == 'mucha_cria_operculada' ? 'selected' : '' }}>Mucha cría operculada</option>
                        <option value="sin_corona_miel"        {{ $patronOld == 'sin_corona_miel' ? 'selected' : '' }}>Sin corona de miel</option>
                        <option value="sospecha_loque"         {{ $patronOld == 'sospecha_loque' ? 'selected' : '' }}>Sospecha de loque</option>
                    </select>
                </div>

                {{-- ENFERMEDADES / PLAGAS --}}
                <div class="col-12 col-md-6 mb-3">
                    <label for="enfermedadPlaga">Enfermedades / plagas *</label>
                    @php $enfOld = old('enfermedadPlaga'); @endphp
                    <select name="enfermedadPlaga" id="enfermedadPlaga" class="form-control" required>
                        <option value="" disabled {{ $enfOld ? '' : 'selected' }}>Seleccione la enfermedad o plaga</option>
                        <option value="ninguna"         {{ $enfOld == 'ninguna' ? 'selected' : '' }}>Ninguna</option>
                        <option value="loque_europea"   {{ $enfOld == 'loque_europea' ? 'selected' : '' }}>Loque europea</option>
                        <option value="loque_americana" {{ $enfOld == 'loque_americana' ? 'selected' : '' }}>Loque americana</option>
                        <option value="varroa"          {{ $enfOld == 'varroa' ? 'selected' : '' }}>Varroa</option>
                        <option value="hormigas"        {{ $enfOld == 'hormigas' ? 'selected' : '' }}>Hormigas</option>
                        <option value="otra"            {{ $enfOld == 'otra' ? 'selected' : '' }}>Otra (especificar en notas)</option>
                    </select>
                </div>

                {{-- NOTAS --}}
                <div class="col-12 mb-3 form-group">
                    <label for="notas" class="mb-2">Notas (observaciones generales)</label>
                    <textarea name="notas" id="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
                </div>

                {{-- BOTÓN --}}
                <div class="col-12 form-group">
                    <button type="submit" class="btn btn-primary w-100">
                        GUARDAR INSPECCIÓN
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
