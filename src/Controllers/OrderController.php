<?php

final class OrderController
{
    public function __construct(
        private OrderService $service,
        private OrderRepository $repository,
        private ReportGenerator $reportGenerator
    ) {
    }

    public function createForm(): void
    {
        $csrfToken = Csrf::token();
        require __DIR__ . '/../../views/order-form.php';
    }

    /** Entrada web deliberadamente delgada: sin SQL, reglas de negocio ni HTML. */
    public function create(): void
    {
        Csrf::assertValid((string) ($_POST['_token'] ?? ''));
        $result = $this->service->procesarPedidoCompleto(Order::fromArray($_POST), (string) ($_POST['notification_type'] ?? 'email'), (string) ($_POST['destination'] ?? ''));
        $_SESSION['flash'] = ['message' => sprintf('Pedido #%d creado correctamente. Total: $ %.2f.', $result['order']->getId(), $result['order']->getTotal() ?? 0.0), 'notification' => $result['notification'], 'events' => $result['events']];
        header('Location: ?accion=listar');
        exit;
    }

    public function index(): void
    {
        $pedidos = array_map(static fn (Order $order): array => $order->toArray(), $this->repository->findAll());
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require __DIR__ . '/../../views/orders.php';
    }

    public function report(): void
    {
        $contenido = $this->reportGenerator->generateDailyReport($this->repository->findAll());
        require __DIR__ . '/../../views/report.php';
    }
}
