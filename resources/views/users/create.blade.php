@extends('administrador.inicio')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Registro de Usuario</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Corrige los siguientes campos:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombres</label>
                    <input type="text" id="nombre" name="nombre"
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="primerApellido" class="form-label">Apellido Paterno</label>
                    <input type="text" id="primerApellido" name="primerApellido"
                        class="form-control @error('primerApellido') is-invalid @enderror"
                        value="{{ old('primerApellido') }}" required>
                    @error('primerApellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="segundoApellido" class="form-label">Apellido Materno</label>
                    <input type="text" id="segundoApellido" name="segundoApellido"
                        class="form-control @error('segundoApellido') is-invalid @enderror"
                        value="{{ old('segundoApellido') }}" >
                    @error('segundoApellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" id="nombreUsuario" name="nombreUsuario"
                        class="form-control @error('nombreUsuario') is-invalid @enderror"
                        value="{{ old('nombreUsuario') }}" required>
                    @error('nombreUsuario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono"
                        class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono') }}" required>
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- ROL -->
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    @if(auth()->user()->rol == 'usuario')
                    <select id="rol" name="rol" class="form-control" required readonly>
                        <option value="colaborador" selected>Colaborador</option>
                    </select>

                    @else
                    <select id="rol" name="rol" class="form-control @error('rol') is-invalid @enderror" required>
                        <option value="usuario" {{ old('rol','usuario') === 'usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="administrador" {{ old('rol','usuario') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    @endif

                    
                </div>

                <div class="d-flex justify-content-end" style="padding-top: 2rem; padding-bottom: 2rem;">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection