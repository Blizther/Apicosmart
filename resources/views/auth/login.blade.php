<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; background-color: #f5f5f5; }
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=password] {
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
        .opciones {
            margin-top: 20px;
            text-align: center;
        }
        .opciones a {
            display: block;
            color: #5D4037;
            margin-top: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="/login">
            @csrf
            <label for="nombreUsuario">Nombre de Usuario</label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" value="{{ old('nombreUsuario') }}" required >

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" value="{{ old('password') }}" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="opciones">
            <a href="#">¿Olvidaste tu contraseña?</a>
            <a href="#">Registrar nuevo usuario</a>
        </div>
    </div>
</body>
</html>

