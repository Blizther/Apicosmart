<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Fondo general */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('/img/fondo.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Contenedor del formulario */
        .form-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.2); /* Transparencia */
            backdrop-filter: blur(10px); /* Difuminado tipo “glassmorphism” */
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            text-align: center;
        }

        /* Logo */
        .logo {
            width: 180px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        /* Labels alineados a la izquierda */
        label {
            display: block;
            text-align: left;
            color: white;
            font-weight: 600;
            margin-bottom: 4px;
        }

        /* Inputs */
        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 12px;
            margin-top: 4px;
            margin-bottom: 16px;
            border: none;
            border-radius: 6px;
            background: rgba(255,255,255,0.8);
        }

        /* Botón */
        button {
            background-color: #F9B233;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        button:hover {
            background-color: #C98C1C; /* Más oscuro para hover */
        }

        /* Enlaces inferiores */
        .opciones {
            margin-top: 20px;
        }

        .opciones a {
            display: block;
            color: #fff;
            margin-top: 8px;
            text-decoration: none;
            font-size: 14px;
        }

        .opciones a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <div style="color:red; text-align:center;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-container">
        <!-- Logo -->
        <img src="/img/logoApicoSmart.jpg" alt="Logo" class="logo">

        <h2>Iniciar Sesión</h2>

        <form method="POST" action="/login">
            @csrf
            <label for="nombreUsuario">Nombre de Usuario</label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" value="{{ old('nombreUsuario') }}" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="opciones">
            <a href="#">¿Olvidaste tu contraseña?</a>
            <a href="#">Registrar nuevo usuario</a>
        </div>
    </div>
</body>
</html>
