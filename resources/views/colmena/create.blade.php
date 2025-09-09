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
            <a href="{{ route('colmena.index')}}">
                <button type="submit" class="btn btn-warning">VOLVER A LISTA</button>
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

        <form action="{{ route('colmena.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="bg-light rounded h-100 p-2 row">
                    <h6 class="mb-4 col-12">Complete el formulario</h6>

                    <div class="form-floating mb-3 col-12 col-md-6">
                        <label for="nombre">Código</label>
                        <input type="text" class="form-control" id="nombre"
                            placeholder="Nombres" name="nombre" value="{{ old('nombre') }}" required autocomplete="off">
                    </div>
                    <div class="form-floating mb-3 col-12 col-md-6">
                        <label for="departamento">Fecha de Fabricación</label>
                        <input type="text" class="form-control" id="departamento"
                            placeholder="Departamento" name="departamento" value="{{ old('departamento') }}" required autocomplete="off">
                    </div>
                    <div class="form-floating mb-3 col-12 col-md-6">
                        <label for="municipio">Cantidad de Marcos</label>
                        <input type="text" class="form-control" id="municipio"
                            placeholder="Municipio" name="municipio" value="{{ old('municipio') }}" required autocomplete="off">
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
  $('.submit').prop('disabled', true);
    let map;
    let marker;
    let selectedLatLng = null;

    function initMap() {
        // Centro inicial del mapa (puedes cambiarlo)
        const initialPosition = { lat: -17.38950, lng: -66.15680 }; // Cochabamba, Bolivia

        // Crear mapa
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialPosition,
            zoom: 13,
        });

        // Detectar clic en el mapa
        map.addListener("click", (e) => {
            selectedLatLng = e.latLng;

            // Si ya hay un marcador, moverlo
            if (marker) {
                marker.setPosition(selectedLatLng);
            } else {
                marker = new google.maps.Marker({
                    position: selectedLatLng,
                    map: map,
                });
            }
        });

        // Botón para guardar
        document.getElementById("saveBtn").addEventListener("click", () => {
            if (selectedLatLng) {
                const lat = selectedLatLng.lat();
                const lng = selectedLatLng.lng();
                $("#latitud").val(lat);
                $("#longitud").val(lng);

                //alert("Ubicación guardada: " + lat + ", " + lng);
                $('.submit').prop('disabled', false);
            } else {
                alert("Primero selecciona una ubicación en el mapa");
            }
        });
    }

    window.onload = initMap;
</script>
<!-- Sale & Revenue End -->
@endsection