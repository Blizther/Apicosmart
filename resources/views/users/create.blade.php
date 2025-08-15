<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f5f5f5;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input[type=text],
        input[type=email],
        input[type=tel] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #5D4037;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        @if ($errors->any())
        <strong>Error:</strong> Corrige los siguientes campos:
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <label for="nombre">Nombres</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="primerApellido">Apellido Paterno</label>
            <input type="text" id="primerApellido" name="primerApellido" required>

            <label for="segundoApellido">Apellido Materno</label>
            <input type="text" id="segundoApellido" name="segundoApellido" required>

            <label for="email">Correo Electronico</label>
            <input type="email" id="email" name="email" required>

            <label for="nombreUsuario">Nombre de Usuario</label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" required>

            <button type="submit">Registrar</button>
        </form>
    </div>
</body>

</html>

