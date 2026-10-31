<?php
$pedidos = $pedidos ?? [];
?>
<h1>Pedidos</h1>

<?php
// ❌ MAL APLICADO: la vista abre la conexion por su cuenta. En el sistema real
//    aca va un SELECT. La vista NO deberia saber que existe una base de datos.
//    ✅ Los datos tienen que llegar desde el controlador: $pedidos ya resuelto.
$conexion = Connection::getInstance();
$pedidos  = [
    ['id' => 1, 'paciente' => 'Juan Perez',  'monto' => 15000, 'tipo' => 'obra_social'],
    ['id' => 2, 'paciente' => 'Ana Gomez',   'monto' => 22000, 'tipo' => 'particular'],
];
?>

<table border="1">
    <tr><th>ID</th><th>Paciente</th><th>Total</th></tr>

    <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= htmlspecialchars((string) ($pedido['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) ($pedido['paciente'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) ($pedido['total'] ?? ($pedido['monto'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
