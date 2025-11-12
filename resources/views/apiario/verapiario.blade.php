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

        <!-- Ubicación -->
        <div class="col-sm-12 col-md-12">
            <div class="bg-light rounded p-4">
                @if($colmenas->count() == 0)
                    <div class="alert alert-danger">
                        Este apiario no tiene colmenas registradas.
                    </div>
                @endif
                <h3>Ubicación</h3>
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
                            <th>Fecha registro</th>
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
                                <td>{{ \Carbon\Carbon::parse($colmena->fechaCreacion)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ ucfirst($colmena->estadoOperativo) }}</td>
                                <td>{{ $colmena->cantidadMarco }}</td>
                                <td>{{ $colmena->modelo }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                        <a href="{{ route('colmenas.verinspeccion', $colmena->idColmena) }}" class="btn btn-primary btn-sm">
                                            Ver Inspecciones
                                        </a>
                                        <a href="{{ route('colmenas.edit', $colmena->idColmena) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>

                                        {{-- Botón eliminar con SweetAlert --}}
                                        <form action="{{ route('colmenas.destroy', $colmena->idColmena) }}" 
                                              method="POST" 
                                              class="m-0 p-0 form-eliminar-colmena">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm btn-eliminar-colmena"
                                                    data-codigo="{{ $colmena->codigo }}">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
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

    // SweetAlert para eliminar colmenas
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-colmena');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form = this.closest('form');
            const codigo = this.getAttribute('data-codigo') || '';

            Swal.fire({
                title: 'Atención',
                text: '¿Estás seguro de que deseas eliminar la colmena con código: ' + codigo + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3A4F26',
                cancelButtonColor: '#F9B233',
                customClass: { popup: 'swal2-apico-popup' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.table-danger {
    background-color: #f8d7da !important;
    color: #842029 !important;
}

/* Alinear botones de acción */
td .d-flex form {
    display: inline-flex !important;
    align-items: center;
    margin: 0;
    padding: 0;
}

td .d-flex .btn {
    margin: 0 2px;
}

/* Estilo del modal SweetAlert */
.swal2-apico-popup {
    border-radius: 16px !important;
}
</style>
@endsection
