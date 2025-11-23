@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">

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

    <div class="row g-4">
        <div class="col-sm-12">
            <a href="<?php echo asset(''); ?>productos/crearproducto">
                <button type="submit" class="btn btn-success">AGREGAR PRODUCTO</button>
            </a>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Panel de Productos</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">Unidad de Medida</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $correlativo=1;
                        ?>
                        @foreach ($productos as $producto)
                            <tr>
                                <th scope="row"><?php echo $correlativo; ?></th>
                                <td>{{ $producto->descripcion }}</td>
                                <td>{{ $producto->unidadMedida }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>{{ $producto->precio }}</td>
                                <td>
                                    @if($producto->imagen)
                                        <img src="{{ asset($producto->imagen) }}" alt="Imagen Actual" width="100"><br><br>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                        {{-- Formulario de eliminación --}}
                                        <form action="{{ route('productos.destroy', $producto->id) }}"
                                              method="POST"
                                              class="m-0 p-0 form-eliminar-producto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-eliminar-producto"
                                                    data-nombre="{{ $producto->descripcion }}">
                                                Eliminar
                                            </button>
                                        </form>

                                        <a href="{{ route('productos.editar', $producto->id) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        $correlativo++;
                        ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

{{-- SweetAlert (si ya lo cargas en tu layout, puedes quitar esta línea) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botonesEliminar = document.querySelectorAll('.btn-eliminar-producto');

        botonesEliminar.forEach(function (boton) {
            boton.addEventListener('click', function () {
                const form   = this.closest('form');
                const nombre = this.getAttribute('data-nombre') || '';

                Swal.fire({
                    title: 'Atención',
                    text: '¿Estás seguro de que deseas eliminar este producto: ' + nombre + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3A4F26',  // verde del proyecto
                    cancelButtonColor: '#F9B233',   // amarillo del proyecto
                    customClass: {
                        popup: 'swal2-apico-popup'
                    }
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
    /* Para redondear y que se parezca al login */
    .swal2-apico-popup {
        border-radius: 16px !important;
    }

    /* Alinear botones de acciones en una sola fila (Eliminar + Editar) */
    td .d-flex form {
        display: inline-flex !important;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    td .d-flex .btn {
        margin: 0 2px;
    }
</style>

@endsection
