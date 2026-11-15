<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte | Laboratorio</title>
    <style>body{font-family:Arial,sans-serif;max-width:900px;margin:32px auto;padding:0 18px;color:#202124}pre{white-space:pre-wrap;background:#f2f4f7;padding:18px;border-radius:6px}</style>
</head>
<body>
    <nav><a href="?accion=listar">Volver a pedidos</a></nav>
    <h1>Reporte de pedidos</h1>
    <p>En esta práctica las opciones de firma, PDF y marca de agua se representan como decoradores de texto; no se genera un PDF ni una firma criptográfica real.</p>
    <pre><?= htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8') ?></pre>
</body>
</html>