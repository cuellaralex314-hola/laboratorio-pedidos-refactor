<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error | Laboratorio</title>
    <style>body{font-family:Arial,sans-serif;max-width:760px;margin:40px auto;padding:0 18px;color:#202124}.error{background:#fff3f3;border-left:4px solid #b3261e;padding:16px}</style>
</head>
<body>
    <h1>No se pudo completar la operación</h1>
    <div class="error"><p><?= htmlspecialchars((string) ($mensaje ?? 'Ocurrió un error inesperado.'), ENT_QUOTES, 'UTF-8') ?></p></div>
    <p>Revisá la configuración y los datos ingresados. Si el problema continúa, consultá el README del proyecto.</p>
    <p><a href="?accion=listar">Volver al inicio</a></p>
</body>
</html>