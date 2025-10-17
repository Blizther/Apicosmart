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

    <div class="row g-4"> <!-- g-4 agrega separación entre filas y columnas -->
    @foreach($apiarios as $index => $apiario)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card apiario-card h-100 text-center">
                <!-- Imagen del apiario -->
                 <!--"{{ $apiario->imagen_url ?? 'https://via.placeholder.com/150' }}" -->
                <img src="{{ asset('img/logoApicoSmart.jpg') }}"
                     class="card-img-top rounded-circle img-rounded mx-auto mt-3" 
                     style="width:120px; height:120px; object-fit:cover;" 
                     alt="Imagen de {{ $apiario->nombre }}">

                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $apiario->nombre }}</h5>
                    <p>Total Colmenas activas: <strong>{{ $apiario->cantidadColmnenasActivas() ?? 0 }}</strong></p>
                    <p class="text-success">Activas: <strong>{{ $apiario->colmenas_activo ?? 0 }}</strong></p>
                    <p class="text-warning">En tratamiento: <strong>{{ $apiario->colmenas_tratamiento ?? 0 }}</strong></p>
                </div>

                <div class="card-footer d-flex justify-content-center gap-2">
                    <a href="" class="btn btn-info btn-sm" title="Ver Detalles">
                        <i class="fa fa-eye"></i>
                    </a>

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

        <!-- Separador visual después de cada fila completa -->
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
    margin-bottom: 30px; /* separación visual entre filas */
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

@endsection