@extends('usuario.inicio')
@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Botón volver -->
    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('apiario.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i>
                VOLVER A LISTA
            </a>
        </div>
    </div>

    <!-- Título del Apiario -->
    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Detalles del Apiario: {{ $apiario->nombre }}</h1>
            </div>
        </div>
    </div>

    <!-- Información general y mapa -->
    <div class="row g-4 mt-2">
        <!-- Información general -->
        <div class="col-sm-12 col-md-12">
    <div class="bg-light rounded p-4">
        <h3>Información General</h3>
        <div class="row align-items-center">
            <!-- Columna izquierda: datos -->
            <div class="col-md-8">
                <p><strong>Vegetación:</strong> {{ $apiario->vegetacion }}</p>
                <p><strong>Altitud:</strong> {{ $apiario->altitud }} metros</p>
                <p><strong>Estado:</strong> {{ $apiario->estado ? 'Activo' : 'Inactivo' }}</p>
            </div>

            <!-- Columna derecha: imagen -->
            @if(!empty($apiario->urlImagen))
            <div class="col-md-4 text-center">
                <img src="{{ asset($apiario->urlImagen) }}" 
                     alt="Imagen del Apiario" 
                     class="img-fluid rounded shadow-sm" 
                     style="max-height: 200px; border-radius: 12px; object-fit: cover;">
            </div>
            @endif
        </div>
    </div>
</div>


        <!-- Ubicación e imagen -->
        <div class="col-sm-12 col-md-12">
            <div class="bg-light rounded p-4">
                <!-- Mensaje si no tiene colmenas -->
                @if($colmenas->count() == 0)
                    <div class="alert alert-danger">
                        Este apiario no tiene colmenas registradas.
                    </div>
                @endif
                <h3>Ubicación</h3>
                <!-- Contenedor del mapa -->
                <div id="map" style="height: 300px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>

    <!-- Tabla de colmenas -->
    <div class="row g-4 mt-4">
        <div class="col-sm-12 col-md-12">
            @if($colmenas->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NRO</th>
                            <th>Código</th>
                            <th>Fecha Fabricación</th>
                            <th>Estado</th>
                            <th>Cantidad de Marcos</th>
                            <th>Modelo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        @foreach ($colmenas as $colmena)
                            <tr class="{{ strtolower($colmena->estadoOperativo) == 'enferma' ? 'table-danger' : '' }}">
                                <th>{{ $correlativo }}</th>
                                <td>{{ $colmena->codigo }}</td>
                                <td>{{ \Carbon\Carbon::parse($colmena->fechaFabricacion)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ ucfirst($colmena->estadoOperativo) }}</td>
                                <td>{{ $colmena->cantidadMarco }}</td>
                                <td>{{ $colmena->modelo }}</td>
                                <td>
                                    <a href="{{ route('colmenas.verinspeccion', $colmena->idColmena) }}" class="btn btn-primary btn-sm">Ver Inspecciones</a>
                                    <a href="{{ route('colmenas.edit', $colmena->idColmena) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('colmenas.destroy', $colmena->idColmena) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta colmena?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @php $correlativo++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-center text-danger mt-3">No hay colmenas registradas para este apiario.</p>
            @endif
        </div>
    </div>
</div>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $apiario->latitud ?? 'null' }};
    var lng = {{ $apiario->longitud ?? 'null' }};
    var cantidadColmenas = {{ $colmenas->count() ?? 0 }};

    if(lat && lng){
        var map = L.map('map').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var popupContent = `<strong>{{ $apiario->nombre }}</strong><br>
                            Vegetación: {{ $apiario->vegetacion ?? 'N/A' }}<br>
                            Altitud: {{ $apiario->altitud ?? 'N/A' }} m<br>`;
        if(cantidadColmenas > 0){
            popupContent += `Total colmenas: ${cantidadColmenas}`;
        } else {
            popupContent += `<span style="color:red; font-weight:bold;">Este apiario no tiene colmenas registradas</span>`;
        }

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup(popupContent)
            .openPopup();
    } else {
        document.getElementById('map').innerHTML = '<p style="color:red;">No hay coordenadas registradas para este apiario</p>';
    }
});
</script>
<style>
    .table-danger {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }
</style>
@endsection
