<?php
$pedidos  = [
    ['id' => 1, 'paciente' => 'Juan Perez',  'monto' => 15000, 'tipo' => 'obra_social'],
    ['id' => 2, 'paciente' => 'Ana Gomez',   'monto' => 22000, 'tipo' => 'particular'],
];
?>
<h1> Pedidos </h1>
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
    <tr><th>ID</th><th>Paciente</th><th>Total</th></tr>

    <?php foreach ($pedidos as $pedido): ?>
            <td><?= $pedido['id'] ?></td>

            <td><?= $pedido['paciente'] ?></td>

            
            <td><?= PriceCalculator::forPatientType($pedido['tipo'])->calculate($pedido['monto']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

