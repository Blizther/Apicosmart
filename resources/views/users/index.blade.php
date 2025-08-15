<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>

    @if(auth()->user()->role === 'administrador')
    <a href="{{ route('administrador.inicio') }}">Volver al Inicio</a>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>





    <h2>Lista de Usuarios</h2>
    <a href="{{ route('users.create') }}">Crear nuevo usuario</a>

    @if(session('success'))
    <p>{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->nombre }}</td>
                <td>{{ $user->primerApellido }}</td>
                <td>{{ $user->segundoApellido }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}">Editar</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>