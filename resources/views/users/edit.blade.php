<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    

    <h2>Editar Usuario</h2>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <label>Nombre:</label><input name="nombre" value="{{ $user->nombre }}" required><br>
        <label>Apellido Paterno:</label><input name="primerApellido" value="{{ $user->primerApellido }}" required><br>
        <label>Apellido Materno:</label><input name="segundoApellido" value="{{ $user->segundoApellido }}" required><br>
        <label>Email:</label><input name="email" value="{{ $user->email }}" type="email" required><br>
        <label>Rol:</label>
        <select name="rol">
            <option value="usuario" {{ $user->role === 'usuario' ? 'selected' : '' }}>Usuario</option>
            <option value="administrador" {{ $user->role === 'administrador' ? 'selected' : '' }}>Administrador</option>
        </select><br>
        <label>Contraseña (opcional):</label><input name="password" type="password"><br>
        <button type="submit">Actualizar</button>
    </form>

    
</body>
</html>





