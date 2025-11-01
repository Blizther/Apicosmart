@extends('usuario.inicio')
@section('content')
<div class="container-fluid pt-4 px-4">

    {{-- Errores --}}
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

    {{-- Volver --}}
    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('apiario.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i>
                VOLVER A LISTA
            </a>
        </div>
    </div>

    {{-- Título --}}
    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1 class="h3 mb-0">Agregar nuevo Apiario</h1>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <form id="apiarioForm" action="{{ route('apiario.store')}}" method="POST" enctype="multipart/form-data" class="row g-4 mt-1">
        @csrf
        <div class="col-sm-12">
            <div class="bg-light rounded h-100 p-3 row">
                <h6 class="mb-4 col-12">Complete el formulario</h6>

                {{-- Nombre (label arriba) --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <small class="stats-label">(no debe ser repetido)</small>
                    <input type="text" class="form-control" id="nombre"
                        placeholder="Nombre" name="nombre" value="{{ old('nombre') }}" required autocomplete="off">
                </div>

                {{-- vegetacion (label arriba) + aquí se muestra la ubicación guardada --}}
                <div class="mb-1 col-12 col-md-6">
                    <label for="vegetacion" class="form-label">Vegetación predominante</label>
                    <select name="vegetacion" id="vegetacion"  class="form-control" required>
                        <option value="eucalipto" {{ old('vegetacion') == 'eucalipto' ? 'selected' : '' }}>eucalipto</option>
                        <option value="tola" {{ old('vegetacion') == 'tola' ? 'selected' : '' }}>tola</option>
                        <option value="muña" {{ old('vegetacion') == 'muña' ? 'selected' : '' }}>muña</option>
                        <option value="clavel" {{ old('vegetacion') == 'clavel' ? 'selected' : '' }}>clavel</option>
                        <option value="margarita" {{ old('vegetacion') == 'margarita' ? 'selected' : '' }}>margarita</option>
                        <option value="fresia" {{ old('vegetacion') == 'fresia' ? 'selected' : '' }}>fresia</option>
                        <option value="retama" {{ old('vegetacion') == 'retama' ? 'selected' : '' }}>retama</option>
                        <option value="chino" {{ old('vegetacion') == 'chino' ? 'selected' : '' }}>chino</option>
                        <option value="ilusión" {{ old('vegetacion') == 'ilusión' ? 'selected' : '' }}>ilusión</option>
                        <option value="Otro" {{ old('vegetacion') == 'otro' ? 'selected' : '' }}>OTRO</option>
                    </select>
                   
                </div>

                {{-- altitud (label arriba) --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="altitud" class="form-label">Altitud*</label>
                    <small class="stats-label">metros/nivel del mar | max 4000</small>
                    <input type="number" class="form-control" id="altitud"
                        placeholder="Altitud" name="altitud" value="{{ old('Altitud') }}" required autocomplete="off" min="0" step="1" max="4000">
                </div>
                {{-- Imagen (label arriba) --}}
                <div class="mb-3 col-12 col-md-6">
                    <label for="urlImagen">Subir Imagen</label>
                        <input type="file" name="urlImagen" class="form-control">
                </div>
                <div class="mb-3 col-12 col-md-6">
                    <label for="ubicacion" class="form-label">Ubicacion</label>
                    <input
                        type="text"
                        id="ubicacion"
                        class="form-control"
                        placeholder="Selecciona en el mapa y pulsa Guardar Ubicación"
                        value="{{ old('latitud') && old('longitud') ? old('latitud').', '.old('longitud') : '' }}"
                        readonly>
                </div>

                {{-- Mapa y guardado de ubicación --}}
                <div class="col-12 mt-5 mb-5 pt-2">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-warning px-4 py-2 rounded-pill my-3" id="saveBtn">
                            Guardar Ubicación
                        </button>
                    </div>
                </div>

                {{-- Coordenadas ocultas --}}
                <input type="hidden" name="latitud" id="latitud" value="">
                <input type="hidden" name="longitud" id="longitud" value="">

                {{-- Enviar --}}
                <div class="col-12">
                    <button type="submit" class="submit btn btn-primary w-100" disabled>GUARDAR APIARIO</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let map, marker, selectedLatLng = null;

    function initMap() {
        const initialPosition = {
            lat: -17.38950,
            lng: -66.15680
        }; // Cochabamba
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialPosition,
            zoom: 13,
        });

        map.addListener("click", (e) => {
            selectedLatLng = e.latLng;
            if (marker) {
                marker.setPosition(selectedLatLng);
            } else {
                marker = new google.maps.Marker({
                    position: selectedLatLng,
                    map
                });
            }
        });

        document.getElementById("saveBtn").addEventListener("click", () => {
    if (!selectedLatLng) {
        alert("Primero selecciona una ubicación en el mapa");
        return;
    }
    const lat = selectedLatLng.lat();
    const lng = selectedLatLng.lng();

    // setea hidden fields que usas en el backend
    document.getElementById("latitud").value = lat;
    document.getElementById("longitud").value = lng;

    // muestra en el input de solo lectura
    document.getElementById("ubicacion").value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

    // habilita el submit
    document.querySelector(".submit").disabled = false;
});
        // Bloquear envío si no hay coordenadas (por si presionan Enter)
        document.getElementById("apiarioForm").addEventListener("submit", (ev) => {
            const lat = document.getElementById("latitud").value;
            const lng = document.getElementById("longitud").value;
            if (!lat || !lng) {
                ev.preventDefault();
                alert("Debes guardar una ubicación antes de enviar.");
            }
        });
    }

    // Exponer callback al global para Google Maps
    window.initMap = initMap;
</script>

{{-- Usa tu misma API key (no cambio parámetros) --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrPcgLHdIkpWRKXLYHX4Ou_JbEWBezWuw&callback=initMap">
</script>
@endsection