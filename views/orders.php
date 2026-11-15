<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedidos | Laboratorio</title>
    <style>
        body{font-family:Arial,sans-serif;max-width:1050px;margin:32px auto;padding:0 18px;color:#202124}
        nav{display:flex;gap:16px;margin-bottom:20px}.actions{display:flex;gap:12px;margin:20px 0}
        table{width:100%;border-collapse:collapse}th,td{border:1px solid #bbb;padding:9px;text-align:left}th{background:#f2f4f7}
        .notice{background:#eef6ee;padding:14px;border-left:4px solid #398445;margin:16px 0;overflow-wrap:anywhere}
        .button{display:inline-block;padding:10px 14px;border-radius:5px;background:#174ea6;color:white;text-decoration:none}
    </style>
</head>
<body>
    <nav><a href="?accion=listar">Pedidos</a><a href="?accion=reporte">Reporte</a></nav>
    <h1>Pedidos del laboratorio</h1>
    <?php if (is_array($flash)): ?>
        <section class="notice" role="status">
            <strong><?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
            <?php if (!empty($flash['notification'])): ?><p><?= htmlspecialchars((string) $flash['notification'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <?php foreach (($flash['events'] ?? []) as $event): ?><p><?= htmlspecialchars((string) $event, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?>
        </section>
    <?php endif; ?>
    <div class="actions"><a class="button" href="?accion=crear">Crear pedido</a><a class="button" href="?accion=reporte">Generar reporte</a></div>
    <?php if ($pedidos === []): ?>
        <p>Todavía no hay pedidos registrados. Creá el primero desde el botón de arriba.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Paciente</th><th>Importe base</th><th>Tipo</th><th>Total</th></tr></thead>
            <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= htmlspecialchars((string) ($pedido['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) ($pedido['paciente'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$ <?= htmlspecialchars(number_format((float) ($pedido['monto'] ?? 0), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) ($pedido['tipo'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$ <?= htmlspecialchars(number_format((float) ($pedido['total'] ?? 0), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
