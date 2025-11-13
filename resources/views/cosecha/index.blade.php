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
    <a href="{{ route('cosechas.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar Cosecha
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Cosechas</h1>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 5%;">NRO</th>
                            <th style="width: 25%;">Colmena - Apiario</th>
                            <th style="width: 10%;">Peso (Kg)</th>
                            <th style="width: 20%;">Fecha Cosecha</th>
                            <th style="width: 25%;">Observaciones</th>
                            <th style="width: 15%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp
                        @foreach ($cosechas as $cosecha)
                            @php
                                $colmena = $cosecha->colmena;
                                $apiario = $colmena ? $colmena->apiario : null;
                            @endphp
                            @if($colmena && $apiario)
                                <tr>
                                    <th scope="row">{{ $correlativo }}</th>
                                    <td>Colmena # {{ $colmena->codigo }} - {{ $apiario->nombre }}</td>
                                    <td>{{ $cosecha->peso }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cosecha->fechaCosecha)->format('d/m/Y') }}</td>
                                    <td>{{ $cosecha->observaciones }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- EDITAR --}}
                                            <a href="{{ route('cosechas.edit', $cosecha->idCosecha) }}"
                                               class="btn btn-sm btn-warning">
                                                Editar
                                            </a>

                                            {{-- ELIMINAR --}}
                                            <form action="{{ route('cosechas.destroy', $cosecha->idCosecha) }}"
                                                  method="POST"
                                                  class="m-0 p-0 form-eliminar-cosecha">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-sm btn-danger btn-eliminar-cosecha"
                                                        data-colmena="Colmena # {{ $colmena->codigo }} - {{ $apiario->nombre }}"
                                                        data-fecha="{{ \Carbon\Carbon::parse($cosecha->fechaCosecha)->format('d/m/Y') }}">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @php $correlativo++; @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-cosecha');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form    = this.closest('form');
            const colmena = this.getAttribute('data-colmena') || '';
            const fecha   = this.getAttribute('data-fecha') || '';

            let texto = '¿Desea eliminar esta cosecha?';
            if (colmena || fecha) {
                texto += ' ' + colmena;
                if (fecha) texto += ' - ' + fecha;
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
