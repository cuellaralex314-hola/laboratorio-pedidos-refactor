<?php

use PHPUnit\Framework\TestCase;

final class MvcTest extends TestCase
{
    public function testIndexRendersOrdersAndEscapesUserSuppliedValues(): void
    {
        $controller = $this->createController([
            new Order(4, '<Paciente & Uno>', 1250.0, 'particular', 1250.0),
        ]);
        $previousSession = $_SESSION ?? [];
        $_SESSION = [
            'flash' => [
                'message' => '<Pedido creado>',
                'notification' => '',
                'events' => [],
            ],
        ];

        ob_start();
        try {
            $controller->index();
            $output = ob_get_contents();
        } finally {
            ob_end_clean();
            $_SESSION = $previousSession;
        }

        $this->assertIsString($output);
        $this->assertStringContainsString('&lt;Paciente &amp; Uno&gt;', $output);
        $this->assertStringContainsString('&lt;Pedido creado&gt;', $output);
        $this->assertStringNotContainsString('<Paciente & Uno>', $output);
    }

    /**
     * @param list<Order> $orders
     */
    private function createController(array $orders): OrderController
    {
        $repository = new class($orders) implements OrderRepository {
            /** @param list<Order> $orders */
            public function __construct(private array $orders)
            {
            }

            public function save(Order $order, float $total): Order
            {
                return $order->withPersistenceData(1, $total);
            }

            public function findAll(): array
            {
                return $this->orders;
            }
        };

        $calculator = new PriceCalculator([
            'particular' => new ParticularStrategy(),
            'obra_social' => new ObraSocialStrategy(),
            'jubilado' => new JubiladoStrategy(),
            'prepaga' => new PrepaidStrategy(),
        ]);
        $service = new OrderService(
            new OrderValidator(),
            $calculator,
            $repository,
            new NotificationSender(new NotificationFactory()),
            new OrderEvents(new OrderSubject())
        );

        return new OrderController($service, $repository, new ReportGenerator());
    }
}
