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
    </div>
</div>

{{-- BOTONES SUPERIORES --}}
<div class="mb-3 d-flex justify-content-between">
    <a href="{{ route('colmenas.index') }}" class="btn btn-warning">
        <i class="fa fa-arrow-left"></i>
        VOLVER A LA LISTA DE COLMENAS
    </a>

    <a href="{{ route('inspeccion.create', $id) }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Agregar inspección
    </a>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Control de inspección</h1>
                <h3>Colmena: {{ $codigoColmena }} - Apiario: {{ $nombreApiario }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Fecha inspección</th>
                            <th scope="col">Estado colmena</th>
                            <th scope="col">Temperamento</th>
                            <th scope="col">Intensidad importación</th>
                            <th scope="col">Celdas reales</th>
                            <th scope="col">Patrón de postura</th>
                            <th scope="col">Enfermedad / plaga</th>
                            <th scope="col">Reserva miel</th>
                            <th scope="col">Reserva polen</th>
                            <th scope="col">Reina</th>
                            <th scope="col" style="width: 12%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $correlativo = 1; @endphp

                        @foreach ($inspecciones as $insp)
                            <tr class="{{ strtolower($insp->estadoOperativo) == 'enferma' ? 'table-danger' : '' }}">
                                <th scope="row">{{ $correlativo }}</th>

                                {{-- Fecha inspección --}}
                                <td>
                                    @if($insp->fechaInspeccion)
                                        {{ \Carbon\Carbon::parse($insp->fechaInspeccion)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $insp->estadoOperativo }}</td>
                                <td>{{ $insp->temperamento }}</td>
                                <td>{{ $insp->intensidadImportacion }}</td>
                                <td>{{ $insp->celdasReales }}</td>
                                <td>{{ $insp->patronPostura }}</td>
                                <td>{{ $insp->enfermedadPlaga }}</td>
                                <td>{{ $insp->reservaMiel }}</td>
                                <td>{{ $insp->reservaPolen }}</td>
                                <td>{{ $insp->estadoReina }}</td>

                                {{-- ACCIONES --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        {{-- EDITAR --}}
                                        <a href="{{ route('inspeccion.edit', $insp->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Editar
                                        </a>

                                        {{-- ELIMINAR --}}
                                        <form action="{{ route('inspeccion.destroy', $insp->id) }}"
                                              method="POST"
                                              class="m-0 p-0 form-eliminar-inspeccion">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-eliminar-inspeccion"
                                                    data-fecha="@if($insp->fechaInspeccion){{ \Carbon\Carbon::parse($insp->fechaInspeccion)->format('d/m/Y') }}@endif"
                                                    data-estado="{{ $insp->estadoOperativo }}"
                                                    data-temperamento="{{ $insp->temperamento }}">
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

{{-- SweetAlert para eliminar inspección --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-inspeccion');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form          = this.closest('form');
            const fecha         = this.getAttribute('data-fecha') || '';
            const estado        = this.getAttribute('data-estado') || '';
            const temperamento  = this.getAttribute('data-temperamento') || '';

            let texto = '¿Desea eliminar esta inspección?';
            let detalle = [];

            if (fecha.trim() !== '') {
                detalle.push(fecha);
            }
            if (estado.trim() !== '') {
                detalle.push('Estado: ' + estado);
            }
            if (temperamento.trim() !== '') {
                detalle.push('Temperamento: ' + temperamento);
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
