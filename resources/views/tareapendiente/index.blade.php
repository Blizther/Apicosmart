@extends('usuario.inicio')
@section('content')

<div class="row g-4">
    <div class="col-sm-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('successdelete'))
            <div class="alert alert-success">{{ session('successdelete') }}</div>
        @endif
        @if (session('successedit'))
            <div class="alert alert-success">{{ session('successedit') }}</div>
        @endif
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('tarea.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar Tarea Pendiente
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Tareas Pendientes</h1>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col" style="width: 5%;">NRO</th>
                            <th scope="col" style="width: 10%;">Colmena</th>
                            <th scope="col" style="width: 10%;">Tipo</th>
                            <th scope="col" style="width: 10%;">Prioridad</th>
                            <th scope="col" style="width: 10%;">Estado</th>
                            <th scope="col" style="width: 15%;">Fecha Inicio</th>
                            <th scope="col" style="width: 15%;">Fecha Fin</th>
                            <th scope="col" style="width: 15%;">Título</th>
                            <th scope="col" style="width: 20%;">Descripción</th>
                            <th scope="col" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        @foreach ($tareas as $tarea)
                            <tr>
                                <th scope="row">{{ $correlativo }}</th>
                                <td>
                                    @if($tarea->colmena && $tarea->colmena->apiario)
                                        Colmena #{{ $tarea->colmena->codigo }} - {{ $tarea->colmena->apiario->nombre }}
                                    @elseif($tarea->colmena)
                                        Colmena #{{ $tarea->colmena->codigo }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ ucfirst($tarea->tipo) }}</td>
                                <td>{{ ucfirst($tarea->prioridad) }}</td>
                                <td>{{ ucfirst($tarea->estado) }}</td>
                                <td>
                                    {{ $tarea->fechaInicio ? \Carbon\Carbon::parse($tarea->fechaInicio)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    {{ $tarea->fechaFin ? \Carbon\Carbon::parse($tarea->fechaFin)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $tarea->titulo }}</td>
                                <td>{{ $tarea->descripcion }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('tarea.edit', $tarea->idTareaPendiente) }}" class="btn btn-sm btn-warning">
                                            Editar
                                        </a>
                                        <form action="{{ route('tarea.destroy', $tarea->idTareaPendiente) }}"
                                            method="POST"
                                            class="m-0 p-0 form-eliminar-tarea">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-eliminar-tarea"
                                                    data-colmena="@if($tarea->colmena && $tarea->colmena->apiario)Colmena #{{ $tarea->colmena->codigo }} - {{ $tarea->colmena->apiario->nombre }}@elseif($tarea->colmena)Colmena #{{ $tarea->colmena->codigo }}@else - @endif"
                                                    data-fecha="{{ $tarea->fechaFin ? \Carbon\Carbon::parse($tarea->fechaFin)->format('d/m/Y') : '-' }}"
                                                    data-titulo="{{ $tarea->titulo }}">
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
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-tarea');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form     = this.closest('form');
            const colmena  = this.getAttribute('data-colmena') || '';
            const fecha    = this.getAttribute('data-fecha') || '';
            const titulo   = this.getAttribute('data-titulo') || '';

            let texto = '¿Desea eliminar esta tarea pendiente?';
            let detalle = [];

            if (colmena.trim() !== '') {
                detalle.push(colmena);
            }
            if (titulo.trim() !== '') {
                detalle.push('"' + titulo + '"');
            }
            if (fecha.trim() !== '' && fecha !== '-') {
                detalle.push(fecha);
            }

            if (detalle.length > 0) {
                texto += ' ' + detalle.join(' - ');
            }

            Swal.fire({
                title: 'Atención',
                text: texto,
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
