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
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->nombre }}</td>
                            <td>{{ $user->primerApellido }}</td>
                            <td>{{ $user->segundoApellido }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($user->rol) }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar usuario?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($users->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
