@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

    <!-- Mensajes de sesión -->
    <div class="mb-4">
        @foreach (['success', 'successdelete', 'successedit'] as $msg)
            @if(session($msg))
                <div class="alert alert-success">
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach
    </div>

    <!-- Botón agregar apiario -->
    <div class="mb-3">
        <a href="{{ route('apiario.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Agregar Apiario
        </a>
    </div>
    <hr>

    <!-- Mapa colapsable -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Ubicación de mis Apiarios</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>

        <div class="ibox-content">
            <div id="map" style="height: 500px; border-radius: 12px;"></div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($apiarios as $index => $apiario)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card apiario-card h-100 text-center" data-url="{{ route('apiario.verapiario', $apiario->idApiario) }}">
                    <!-- Imagen del apiario -->
                    <img src="{{ asset('img/logoApicoSmart.jpg') }}"
                         class="card-img-top rounded-circle img-rounded mx-auto mt-3" 
                         style="width:120px; height:120px; object-fit:cover;" 
                         alt="Imagen de {{ $apiario->nombre }}">

                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $apiario->nombre }}</h5>
                        <p>Total Colmenas: <strong>{{ $apiario->cantidadColmnenasActivas() ?? 0 }}</strong></p>
                        <p class="text-success">Activas: <strong>{{ $apiario->colmenas_activo ?? 0 }}</strong></p>
                        <p class="text-warning">En tratamiento: <strong>{{ $apiario->colmenas_tratamiento ?? 0 }}</strong></p>
                    </div>

                    <div class="card-footer d-flex justify-content-center gap-2">
                        <a href="{{ route('apiario.edit', $apiario->idApiario) }}" 
                           class="btn btn-warning btn-sm" title="Editar">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="{{ route('apiario.destroy', $apiario->idApiario) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este apiario?')" 
                                title="Eliminar">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(($index + 1) % 3 == 0)
                <div class="w-100 my-3"><hr></div>
            @endif
        @endforeach
    </div>
</div>

<!-- Estilos personalizados -->
<style>
.apiario-card {
    background-color: #EDD29C;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 30px;
    cursor: pointer;
}

.apiario-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}

.apiario-card .card-footer {
    background: transparent;
    border-top: none;
}
</style>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mapa Leaflet
    var map = L.map('map').setView([-33.45, -70.66], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var apiarios = @json($apiarios);

    if (apiarios.length === 0) {
        console.log("No hay apiarios para mostrar en el mapa.");
        return;
    }

    var markers = L.featureGroup();

    apiarios.forEach(apiario => {
        if (apiario.latitud && apiario.longitud) {
            var marker = L.marker([apiario.latitud, apiario.longitud])
                .bindPopup(`<strong>${apiario.nombre}</strong><br>
                            Vegetación: ${apiario.vegetacion ?? 'N/A'}<br>
                            Altitud: ${apiario.altitud ?? 'N/A'} m`);
            markers.addLayer(marker);
        }
    });

    map.addLayer(markers);
    map.fitBounds(markers.getBounds());

    // Hacer la tarjeta clickeable excepto los botones
    document.querySelectorAll('.apiario-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Ignorar clics en botones
            if (!e.target.closest('a') && !e.target.closest('button')) {
                window.location.href = card.dataset.url;
            }
        });
    });
});
</script>

@endsection
