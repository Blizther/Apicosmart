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
                            <th scope="col">NRO</th>
                            <th scope="col">Colmena</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Prioridad</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Inicio</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Título</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $correlativo = ($tareas->currentPage() - 1) * $tareas->perPage() + 1;
                        @endphp

                        @forelse ($tareas as $tarea)
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

                                <td>{{ $tarea->fechaInicio ? \Carbon\Carbon::parse($tarea->fechaInicio)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $tarea->fechaFin ? \Carbon\Carbon::parse($tarea->fechaFin)->format('d/m/Y') : '-' }}</td>

                                <td>{{ $tarea->titulo }}</td>
                                <td>{{ $tarea->descripcion }}</td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('tarea.edit', $tarea->idTareaPendiente) }}"
                                           class="btn btn-sm btn-warning">Editar</a>

                                        @if(auth()->user()->rol === 'usuario')
                                            <form action="{{ route('tarea.destroy', $tarea->idTareaPendiente) }}"
                                                  method="POST"
                                                  class="m-0 p-0 form-eliminar-tarea">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-sm btn-danger btn-eliminar-tarea"
                                                        data-colmena="@if($tarea->colmena && $tarea->colmena->apiario)
                                                            Colmena #{{ $tarea->colmena->codigo }} - {{ $tarea->colmena->apiario->nombre }}
                                                        @elseif($tarea->colmena)
                                                            Colmena #{{ $tarea->colmena->codigo }}
                                                        @else - @endif"
                                                        data-fecha="{{ $tarea->fechaFin ? \Carbon\Carbon::parse($tarea->fechaFin)->format('d/m/Y') : '-' }}"
                                                        data-titulo="{{ $tarea->titulo }}">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @php $correlativo++; @endphp
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No hay tareas pendientes registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $tareas->links() }}
            </div>
        </div>
    </div>
</div>


{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-eliminar-tarea').forEach(btn => {
        btn.addEventListener('click', function () {
            const form   = this.closest('form');
            const col    = this.dataset.colmena || '';
            const fecha  = this.dataset.fecha || '';
            const titulo = this.dataset.titulo || '';

            let texto = '¿Desea eliminar esta tarea pendiente?';
            let info  = [];

            if (col.trim()) info.push(col);
            if (titulo.trim()) info.push('"' + titulo + '"');
            if (fecha.trim() && fecha !== '-') info.push(fecha);

            if (info.length > 0) texto += ' ' + info.join(' - ');

            Swal.fire({
                title: 'Atención',
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
            }).then(e => { if (e.isConfirmed) form.submit(); });
        });
    });
});
</script>

@endsection
