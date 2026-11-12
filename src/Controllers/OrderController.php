<?php

class OrderController
{
    private OrderService $orderService;

    public function __construct(?OrderService $orderService = null)
    {
        $this->orderService = $orderService ?? $this->createOrderService();
    }

    public function create(): void
    {
        try {
            $this->orderService->procesarPedidoCompleto(
                new Order(
                    (int) ($_GET['id'] ?? 1),
                    (string) ($_GET['paciente'] ?? 'Juan Perez'),
                    (float) ($_GET['monto'] ?? 15000),
                    (string) ($_GET['tipo'] ?? 'obra_social')
                ),
                'email',
                'paciente@mail.com'
            );
        } catch (InvalidArgumentException $exception) {
            http_response_code(400);
            echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
        }
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

    private function createOrderService(): OrderService
    {
        $events = new OrderEvents();
        $events->subscribe(new DashboardObserver());

        return new OrderService(
            new OrderValidator(),
            new OrderRepository(Connection::getInstance()),
            new NotificationSender(),
            $events
        );
    }
}
