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

    <!-- Mapa colapsable (INSPINIA) -->
    <div class="ibox float-e-margins collapsed"><!-- importante: clase collapsed -->
        <div class="ibox-title">
            <h5>Ubicación de mis Apiarios</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    {{-- Ojo: Inspinia usa fa-chevron-up y cambia el ícono con la clase collapsed --}}
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
                <div class="card apiario-card text-center" data-url="{{ route('apiario.verapiario', $apiario->idApiario) }}">
                    
                    <!-- Contenedor fijo de imagen -->
                    <div class="apiario-image-container">
                        @if($apiario->urlImagen)
                            <img src="{{ asset($apiario->urlImagen) }}" 
                                 alt="Imagen del Apiario" 
                                 class="apiario-image">
                        @else
                            <img src="{{ asset('uploads/defaultApiario.jpg') }}" 
                                 alt="Imagen por defecto del Apiario" 
                                 class="apiario-image">
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold">{{ $apiario->nombre }}</h5>
                            <p>Total Colmenas: <strong>{{ $apiario->cantidadColmnenasActivas() ?? 0 }}</strong></p>
                            <p class="text-success">Activas: <strong>{{ $apiario->cantidadColmenasOperativaActiva() ?? 0 }}</strong></p>
                            <p class="text-warning">Enfermas: <strong>{{ $apiario->cantidadColmenasEnfermas() ?? 0 }}</strong></p>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-center gap-2">
                        <a href="{{ route('apiario.edit', $apiario->idApiario) }}" 
                           class="btn btn-warning btn-sm" title="Editar">
                            <i class="fa fa-edit"></i>
                        </a>

                        {{-- Botón eliminar con SweetAlert --}}
                        <form action="{{ route('apiario.destroy', $apiario->idApiario) }}" 
                              method="POST" 
                              class="d-inline form-eliminar-apiario">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="btn btn-danger btn-sm btn-eliminar-apiario"
                                    data-nombre="{{ $apiario->nombre }}"
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
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Contenedor de imagen fijo */
.apiario-image-container {
    width: 100%;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.apiario-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
    // ==== Inicializar mapa normalmente ====
    var map = L.map('map').setView([-33.45, -70.66], 6); // valor inicial, luego se ajusta con fitBounds

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var apiarios = @json($apiarios);
    var markers = L.featureGroup();

    if (apiarios.length > 0) {
        apiarios.forEach(apiario => {
            if (apiario.latitud && apiario.longitud) {
                var marker = L.marker([apiario.latitud, apiario.longitud])
                    .bindPopup(
                        `<strong>${apiario.nombre}</strong><br>
                         Vegetación: ${apiario.vegetacion ?? 'N/A'}<br>
                         Altitud: ${apiario.altitud ?? 'N/A'} m`
                    );
                markers.addLayer(marker);
            }
        });

        if (markers.getLayers().length > 0) {
            map.addLayer(markers);
            map.fitBounds(markers.getBounds());
        }
    }

    // ==== Arreglar tamaño cuando se abre el panel ====
    if (window.$) {
        $(document).on('click', '.collapse-link', function () {
            var ibox = $(this).closest('.ibox');

            // Antes del click el ibox tiene o no la clase 'collapsed'
            var seVaAbrir = ibox.hasClass('collapsed');

            if (seVaAbrir) {
                // Esperar a que termine la animación del slideDown de Inspinia
                setTimeout(function () {
                    map.invalidateSize();
                    if (markers.getLayers().length > 0) {
                        map.fitBounds(markers.getBounds());
                    }
                }, 400);
            }
        });
    }

    // Tarjeta clickeable
    document.querySelectorAll('.apiario-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a') && !e.target.closest('button')) {
                window.location.href = card.dataset.url;
            }
        });
    });
});
</script>

<!-- SweetAlert confirmación eliminación -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-apiario');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form = this.closest('form');
            const nombre = this.getAttribute('data-nombre') || '';

            Swal.fire({
                title: 'Atención',
                text: '¿Estás seguro de que deseas eliminar el apiario: ' + nombre + '?',
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

<style>
.swal2-apico-popup {
    border-radius: 16px !important;
}
</style>

@endsection
