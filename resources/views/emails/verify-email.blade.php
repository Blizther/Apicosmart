<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $user->nombre }} {{ $user->primerApellido }}</h2>

    <p>Te dieron acceso a <strong>ApicoSmart</strong>.</p>

    <p>Para activar tu cuenta y confirmar tu correo, haz clic aquí:</p>

    <p>
        <a href="{{ $url }}" style="padding:10px 15px; background:#1d9bf0; color:white; text-decoration:none; border-radius:6px;">
            Verificar correo
        </a>
    </p>

    <p>Si tú no pediste esta cuenta, ignora este mensaje.</p>
</body>
</html>
