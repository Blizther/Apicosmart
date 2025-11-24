@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

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

    {{-- Botones de agregar: SOLO para usuario --}}
    @if(auth()->user()->rol === 'usuario')
    <div class="mb-3">
        <a href="{{ route('colmenas.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Agregar colmena
        </a>
        <a href="{{ route('colmenas.createLote') }}" class="btn btn-primary ms-2">
            <i class="fa fa-layer-group"></i> Agregar colmenas por lote
        </a>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de colmenas</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre de Apiario</th>
                            <th scope="col">Fecha instalación</th>
                            <th scope="col">Estado Operativo</th>
                            <th scope="col">Cantidad de Marcos</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $correlativo = ($colmenas->currentPage() - 1) * $colmenas->perPage() + 1;
                        @endphp

                        @forelse ($colmenas as $colmena)
                            <tr class="{{ strtolower($colmena->estadoOperativo) == 'enferma' ? 'table-danger' : '' }}">
                                <th scope="row">{{ $correlativo }}</th>

                                <td>{{ $colmena->codigo }}</td>

                                <td>{{ $colmena->apiario->nombre }}</td>

                                <td>
                                    @if($colmena->fechaInstalacionFisica)
                                        {{ \Carbon\Carbon::parse($colmena->fechaInstalacionFisica)->format('d/m/Y') }}
                                    @else
                                        Sin registro
                                    @endif
                                </td>

                                <td>{{ ucfirst($colmena->estadoOperativo) }}</td>
                                <td>{{ $colmena->cantidadMarco }}</td>
                                <td>{{ $colmena->modelo }}</td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                                        {{-- Ver inspecciones --}}
                                        <a href="{{ route('inspeccion.index', $colmena->idColmena) }}"
                                           class="btn btn-primary btn-sm">
                                            Ver inspecciones
                                        </a>

                                        {{-- Detalles --}}
                                        <a href="{{ route('colmenas.show', $colmena->idColmena) }}"
                                           class="btn btn-info btn-sm">
                                            Detalles
                                        </a>

                                        {{-- Editar / Eliminar SOLO usuario --}}
                                        @if(auth()->user()->rol === 'usuario')
                                            <a href="{{ route('colmenas.edit', $colmena->idColmena) }}"
                                               class="btn btn-warning btn-sm">
                                                Editar
                                            </a>

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
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @php $correlativo++; @endphp
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No hay colmenas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $colmenas->links() }}
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
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

<style>
.swal2-apico-popup {
    border-radius: 16px !important;
}
td .d-flex form {
    display: inline-flex !important;
    align-items: center;
}
td .d-flex .btn {
    margin: 0 2px;
}
</style>

@endsection
