@extends('usuario.inicio')
@section('content')

<!-- en esta vista deben visualizarse todos los tratamientos realizados a las colmenas del usuario -->
<div class="row g-4">
    <div class="col-sm-12">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('successdelete'))
            <div class="alert alert-success">
                {{ session('successdelete') }}
            </div>
        @endif

        @if (session('successedit'))
            <div class="alert alert-success">
                {{ session('successedit') }}
            </div>
        @endif
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('tratamiento.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar tratamiento
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Tratamientos</h1>
            </div>
        </div>
    </div>

    <!-- Aquí va el contenido específico de la vista de tratamientos -->
    <div class="row g-4 mt-2">
        <div class="col-sm-12">

            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">NRO</th>
                                <th scope="col">Colmena - Apiario</th>
                                <th scope="col">Plaga/enfermedad</th>
                                <th scope="col">Tratamiento aplicado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                // correlativo según página actual
                                $correlativo = ($tratamientos->currentPage() - 1) * $tratamientos->perPage() + 1; 
                            @endphp

                            @forelse ($tratamientos as $tratamiento)
                                @php
                                    $colmena = $tratamiento->colmena;
                                @endphp

                                <tr>
                                    <th scope="row">{{ $correlativo }}</th>

                                    <td>
                                        @if($colmena && $colmena->apiario)
                                            Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                                        @elseif($colmena)
                                            Colmena #{{ $colmena->codigo }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td>{{ $tratamiento->problemaTratado }}</td>
                                    <td>{{ $tratamiento->tratamientoAdministrado }}</td>
                                    <td>{{ \Carbon\Carbon::parse($tratamiento->fechaAdministracion)->format('d/m/Y') }}</td>
                                    <td>{{ $tratamiento->descripcion }}</td>

                                    {{-- ACCIONES --}}
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- EDITAR: usuario y colaborador --}}
                                            <a href="{{ route('tratamiento.edit', $tratamiento->idTratamiento) }}"
                                               class="btn btn-sm btn-warning">
                                                Editar
                                            </a>

                                            {{-- ELIMINAR: SOLO usuario (apicultor), NO colaborador --}}
                                            @if(auth()->user()->rol === 'usuario')
                                                <form action="{{ route('tratamiento.destroy', $tratamiento->idTratamiento) }}"
                                                      method="POST"
                                                      class="m-0 p-0 form-eliminar-tratamiento">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger btn-eliminar-tratamiento"
                                                            data-colmena="@if($colmena && $colmena->apiario)Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}@elseif($colmena)Colmena #{{ $colmena->codigo }}@else - @endif"
                                                            data-fecha="{{ \Carbon\Carbon::parse($tratamiento->fechaAdministracion)->format('d/m/Y') }}"
                                                            data-problema="{{ $tratamiento->problemaTratado }}">
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
                                    <td colspan="7" class="text-center text-muted">
                                        No hay tratamientos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Links de paginación --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $tratamientos->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SweetAlert para eliminar --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-tratamiento');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form     = this.closest('form');
            const colmena  = this.getAttribute('data-colmena') || '';
            const fecha    = this.getAttribute('data-fecha') || '';
            const problema = this.getAttribute('data-problema') || '';

            let texto = '¿Desea eliminar este tratamiento?';
            let detalle = [];

            if (colmena.trim() !== '') {
                detalle.push(colmena);
            }
            if (problema.trim() !== '') {
                detalle.push(problema);
            }
            if (fecha.trim() !== '') {
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
