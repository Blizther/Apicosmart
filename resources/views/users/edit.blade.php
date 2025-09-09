@extends('administrador.inicio')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Editar Usuario</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $user->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="primerApellido" class="form-control" value="{{ $user->primerApellido }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="segundoApellido" class="form-control" value="{{ $user->segundoApellido }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                        value="{{ old('telefono', $user->telefono) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre de usuario</label>
                    <input type="text" name="nombreUsuario" class="form-control"
                        value="{{ old('nombreUsuario', $user->nombreUsuario) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="usuario" {{ $user->rol === 'usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="administrador" {{ $user->rol === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar vacío si no desea cambiar">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection