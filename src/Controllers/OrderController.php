<?php

class OrderController
{
    private OrderService $orderService;

    public function __construct(?OrderService $orderService = null)
    {
        $this->orderService = $orderService ?? new OrderService();
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
