@extends('administrador.inicio')

@section('content')
<div class="container mt-4">

    @if(auth()->user()->role === 'administrador')
        <a href="{{ route('administrador.inicio') }}" class="btn btn-secondary mb-3">← Volver al Inicio</a>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Lista de Usuarios</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Crear nuevo usuario</a>
    </div>

    {{-- 🔎 Buscador --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-3 d-flex" role="search">
        <input type="text"
               name="q"
               class="form-control me-2"
               placeholder="Buscar por nombre, apellidos o email…"
               value="{{ $q ?? request('q') }}">
        <button type="submit" class="btn btn-outline-secondary">Buscar</button>

        @if(!empty($q))
            <a href="{{ route('users.index') }}" class="btn btn-link ms-2">Limpiar</a>
        @endif
    </form>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->nombre }}</td>
                                <td>{{ $user->primerApellido }}</td>
                                <td>{{ $user->segundoApellido }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($user->rol) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div style="display: flex; gap: 1rem;">
                                        {{-- Editar --}}
                                        <a href="{{ route('users.edit', $user) }}">
                                            <button class="btn btn-sm btn-warning" title="editar">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </button>
                                        </a>

                                        {{-- Eliminar con SweetAlert2 --}}
                                        <form action="{{ route('users.destroy', $user) }}"
                                              method="POST"
                                              class="m-0 p-0 form-eliminar-usuario">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-eliminar-usuario"
                                                    title="eliminar"
                                                    data-nombre="{{ $user->nombre }} {{ $user->primerApellido }} {{ $user->segundoApellido }}"
                                                    data-email="{{ $user->email }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    @if(!empty($q))
                                        No se encontraron resultados para “{{ $q }}”.
                                    @else
                                        No hay usuarios registrados.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if(method_exists($users, 'links'))
                <div class="mt-2">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-usuario');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const form   = this.closest('form');
            const nombre = this.getAttribute('data-nombre') || '';
            const email  = this.getAttribute('data-email') || '';

            let texto = '¿Desea eliminar este usuario?';
            let detalle = [];

            if (nombre.trim() !== '') {
                detalle.push(nombre);
            }
            if (email.trim() !== '') {
                detalle.push(email);
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
