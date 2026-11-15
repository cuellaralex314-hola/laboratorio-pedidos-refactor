<?php

use PHPUnit\Framework\TestCase;

final class MvcSrpTest extends TestCase
{
    public function testReportActionRendersReportGeneratedFromRepositoryOrders(): void
    {
        $orders = [
            new Order(2, 'Paciente Uno', 1000.0, 'particular', 1000.0),
            new Order(1, 'Paciente Dos', 2000.0, 'jubilado', 1000.0),
        ];
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
        $controller = new OrderController(
            $service,
            $repository,
            new ReportGenerator()
        );

        ob_start();
        try {
            $controller->report();
            $output = ob_get_contents();
        } finally {
            ob_end_clean();
        }

        $this->assertIsString($output);
        $this->assertStringContainsString('Pedidos registrados: 2', $output);
        $this->assertStringContainsString('Total acumulado: $ 2000.00', $output);
        $this->assertStringContainsString('+ firma digital + PDF + marca de agua', $output);
    }
}
