<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear pedido | Laboratorio</title>
    <style>
        body{font-family:Arial,sans-serif;max-width:760px;margin:32px auto;padding:0 18px;color:#202124}
        label{display:block;margin:14px 0 6px;font-weight:bold}input,select{box-sizing:border-box;width:100%;padding:10px;border:1px solid #aaa;border-radius:5px}
        button,.link{display:inline-block;margin-top:18px;padding:10px 14px;border:0;border-radius:5px;background:#174ea6;color:white;text-decoration:none;cursor:pointer}
        nav{display:flex;gap:14px;margin-bottom:20px}
    </style>
</head>
<body>
    <nav><a href="?accion=listar">Pedidos</a><a href="?accion=reporte">Reporte</a></nav>
    <h1>Crear pedido</h1>
    <p>El sistema calcula el total de acuerdo con el tipo de paciente. Los mensajes de notificación son simulados.</p>
    <form method="post" action="?accion=crear">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <label for="paciente">Nombre del paciente</label>
        <input id="paciente" name="paciente" maxlength="150" required value="<?= htmlspecialchars((string) ($_POST['paciente'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <label for="monto">Importe base ($)</label>
        <input id="monto" name="monto" type="number" min="0.01" step="0.01" required value="<?= htmlspecialchars((string) ($_POST['monto'] ?? '15000'), ENT_QUOTES, 'UTF-8') ?>">

        <label for="tipo">Tipo de paciente</label>
        <select id="tipo" name="tipo" required>
            <?php foreach (['particular' => 'Particular (sin descuento)', 'obra_social' => 'Obra social (30 %)', 'jubilado' => 'Jubilado (50 %)', 'prepaga' => 'Prepaga (20 %)'] as $value => $label): ?>
                <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= (($_POST['tipo'] ?? 'obra_social') === $value) ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>

        <label for="notification_type">Tipo de notificación</label>
        <select id="notification_type" name="notification_type" required>
            <option value="email">Email</option>
            <option value="sms">SMS</option>
            <option value="whatsapp">WhatsApp</option>
        </select>

        <label for="destination">Destino de la notificación</label>
        <input id="destination" name="destination" maxlength="190" required value="<?= htmlspecialchars((string) ($_POST['destination'] ?? 'paciente@example.com'), ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Guardar pedido</button>
        <a class="link" href="?accion=listar">Cancelar</a>
    </form>
</body>
</html>