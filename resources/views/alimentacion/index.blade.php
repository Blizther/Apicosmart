@extends('usuario.inicio')
@section('content')

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

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('alimentacion.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar Alimentación
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Alimentación</h1>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">NRO</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Colmena - Apiario</th>
                                <th scope="col">Alimento</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Motivo</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $correlativo = ($alimentaciones->currentPage() - 1) * $alimentaciones->perPage() + 1; 
                            @endphp

                            @forelse ($alimentaciones as $alimentacion)
                                @php
                                    $colmena = $alimentacion->colmena;
                                    $apiario = $colmena ? $colmena->apiario : null;
                                @endphp

                                <tr>
                                    <th scope="row">{{ $correlativo }}</th>

                                    {{-- Fecha --}}
                                    <td>{{ \Carbon\Carbon::parse($alimentacion->fechaSuministracion)->format('d/m/Y') }}</td>

                                    {{-- Colmena - Apiario --}}
                                    <td>
                                        Colmena #{{ $colmena->codigo }} - {{ $apiario->nombre }}
                                    </td>

                                    {{-- Alimento --}}
                                    <td>{{ $alimentacion->tipoAlimento }}</td>

                                    {{-- Cantidad + unidad --}}
                                    <td>
                                        {{ $alimentacion->cantidad }}
                                        @if($alimentacion->unidadMedida == 'gr')
                                            g
                                        @elseif($alimentacion->unidadMedida == 'Kg')
                                            kg
                                        @elseif($alimentacion->unidadMedida == 'ml')
                                            mL
                                        @elseif($alimentacion->unidadMedida == 'L')
                                            L
                                        @else
                                            {{ $alimentacion->unidadMedida }}
                                        @endif
                                    </td>

                                    {{-- Motivo --}}
                                    <td>{{ $alimentacion->motivo }}</td>

                                    {{-- Observaciones --}}
                                    <td>{{ $alimentacion->observaciones }}</td>

                                    {{-- ACCIONES --}}
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- EDITAR: usuario y colaborador --}}
                                            <a href="{{ route('alimentacion.edit', $alimentacion->idalimentacion) }}"
                                               class="btn btn-sm btn-warning">
                                                Editar
                                            </a>

                                            {{-- ELIMINAR: SOLO usuario (apicultor), NO colaborador --}}
                                            @if(auth()->user()->rol === 'usuario')
                                                <form action="{{ route('alimentacion.destroy', $alimentacion->idalimentacion) }}"
                                                      method="POST"
                                                      class="m-0 p-0 form-eliminar-alimentacion">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                            class="btn btn-sm btn-danger btn-eliminar-alimentacion"
                                                            data-colmena="Colmena #{{ $colmena->codigo }} - {{ $apiario->nombre }}"
                                                            data-fecha="{{ \Carbon\Carbon::parse($alimentacion->fechaSuministracion)->format('d/m/Y') }}"
                                                            data-alimento="{{ $alimentacion->tipoAlimento }}">
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
                                        No hay registros de alimentación.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Links de paginación --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $alimentaciones->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert para eliminar --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-alimentacion');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form     = this.closest('form');
            const colmena  = this.getAttribute('data-colmena') || '';
            const fecha    = this.getAttribute('data-fecha') || '';
            const alimento = this.getAttribute('data-alimento') || '';

            let texto = '¿Desea eliminar este registro de alimentación?';
            let detalle = [];

            if (colmena.trim() !== '') {
                detalle.push(colmena);
            }
            if (alimento.trim() !== '') {
                detalle.push(alimento);
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
