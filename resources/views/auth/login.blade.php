<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('img/abeja.png') }}">
    <title>Inicio de Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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

        .form-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            text-align: center;
        }

        .logo {
            width: 180px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            color: white;
            font-weight: 600;
            margin-bottom: 4px;
        }

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
            background-color: #C98C1C;
        }

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

        /* === ESTILOS PARA LA ALERTA EMERGENTE PERSONALIZADA === */
        .alert-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4); /* fondo oscuro semi-transparente */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .alert-box {
            background: rgba(255, 255, 255, 0.9); /* tarjetita clara */
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 20px 24px;
            max-width: 320px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            text-align: center;
            font-family: sans-serif;
        }

        .alert-box h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
            color: #2b2b2b;
        }

        .alert-box p {
            margin: 0 0 16px 0;
            font-size: 14px;
            color: #444;
        }

        .alert-btn {
            background-color: #F9B233; /* tu color del botón login */
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            padding: 10px 16px;
            cursor: pointer;
            width: 100%;
        }

        .alert-btn:hover {
            background-color: #C98C1C;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <!-- ya no mostramos el error rojo suelto -->
        <div style="display:none; color:red; text-align:center;">
            {{ $errors->first() }}
        </div>

        <!-- ALERTA EMERGENTE PERSONALIZADA -->
        <div class="alert-overlay" id="customAlert">
            <div class="alert-box">
                <h3>Atención</h3>
                <p>Usuario y/o Contraseña inválida</p>
                <button class="alert-btn" onclick="cerrarAlerta()">Aceptar</button>
            </div>
        </div>

        <script>
    function cerrarAlerta() {
        var alerta = document.getElementById('customAlert');
        if (alerta) {
            alerta.style.display = 'none';
        }
    }

    // Permitir cerrar con Enter o Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === 'Escape') {
            cerrarAlerta();
        }
    });
</script>

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
        </div>
    </div>
</body>
</html>
