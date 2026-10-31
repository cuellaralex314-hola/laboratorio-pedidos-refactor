<?php

class OrderController
{
    private OrderService $orderService;

    public function __construct(?OrderService $orderService = null)
    {
        $this->orderService = $orderService ?? new OrderService();
        // ❌ 1. Entrada sin validar ni sanitizar
        $id       = (int) ($_GET['id'] ?? 1);
        $paciente = $_GET['paciente'] ?? 'Juan Perez';
        $monto    = (float) ($_GET['monto'] ?? 15000);
        $tipo     = $_GET['tipo'] ?? 'obra_social';

        // ❌ 2. Regla de negocio dentro del controlador (deberia ser Strategy)
        $total = $tipo === 'obra_social' ? $monto * 0.7 : $monto;

        // ❌ 3. SQL dentro del controlador (deberia ser Repository)
        $conexion = Connection::getInstance();
        $conexion->ejecutar("INSERT INTO orders VALUES ({$id}, '{$paciente}', {$total})");

        // ❌ 4. Notificacion resuelta otra vez con if (deberia ser Factory)
        $notificador = new NotificationSender();
        $notificador->enviar('email', 'paciente@mail.com', "Pedido {$id} creado");

        // ❌ 5. HTML impreso desde el controlador (deberia ser una View)
        echo "<h1>Pedido creado</h1>";
        echo "<p>Paciente: {$paciente}</p>";       // ❌ ademas, sin escapar: XSS
        echo "<p>Total: $ {$total}</p>";
    }

    public function create(): void
    {
        $this->orderService->procesarPedidoCompleto(
            (int) ($_GET['id'] ?? 1),
            (string) ($_GET['paciente'] ?? 'Juan Perez'),
            (float) ($_GET['monto'] ?? 15000),
            (string) ($_GET['tipo'] ?? 'obra_social'),
            'email',
            'paciente@mail.com'
        );
    }

    public function index(): void
    {
        require __DIR__ . '/../../views/orders.php';
    }

    public function report(): void
    {
        $generador = new ReportGenerator();
        echo $generador->generate('Pedidos del dia', true, true, true);
    }
}
