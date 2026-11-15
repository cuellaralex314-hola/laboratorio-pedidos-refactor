<?php

use PHPUnit\Framework\TestCase;

final class FacadeSrpTest extends TestCase
{
    public function testFacadeCoordinatesValidationPricingPersistenceNotificationAndEvents(): void
    {
        $repository = $this->createRepository();
        $subject = new OrderSubject([
            new EmailObserver(),
            new SmsObserver(),
            new DashboardObserver(),
        ]);
        $service = new OrderService(
            new OrderValidator(),
            $this->createCalculator(),
            $repository,
            new NotificationSender(new NotificationFactory()),
            new OrderEvents($subject)
        );

        $result = $service->procesarPedidoCompleto(
            new Order(null, 'Paciente', 20000.0, 'obra_social'),
            'email',
            'paciente@example.com'
        );

        $this->assertSame(1, $result['order']->getId());
        $this->assertSame(14000.0, $result['order']->getTotal());
        $this->assertStringContainsString('EMAIL simulado', $result['notification']);
        $this->assertCount(3, $result['events']);
        $this->assertCount(1, $repository->findAll());
    }

    public function testUnsupportedPatientTypeIsRejectedBeforePersistence(): void
    {
        $repository = $this->createRepository();
        $service = new OrderService(
            new OrderValidator(),
            $this->createCalculator(),
            $repository,
            new NotificationSender(new NotificationFactory()),
            new OrderEvents(new OrderSubject())
        );

        $this->expectException(InvalidArgumentException::class);
        try {
            $service->procesarPedidoCompleto(
                new Order(null, 'Paciente', 1000.0, 'tipo_desconocido'),
                'email',
                'paciente@example.com'
            );
        } finally {
            $this->assertSame([], $repository->findAll());
        }
    }

    private function createCalculator(): PriceCalculator
    {
        return new PriceCalculator([
            'particular' => new ParticularStrategy(),
            'obra_social' => new ObraSocialStrategy(),
            'jubilado' => new JubiladoStrategy(),
            'prepaga' => new PrepaidStrategy(),
        ]);
    }

    private function createRepository(): OrderRepository
    {
        return new class implements OrderRepository {
            /** @var list<Order> */
            private array $orders = [];

            private int $nextId = 1;

            public function save(Order $order, float $total): Order
            {
                $saved = $order->withPersistenceData(
                    $order->getId() ?? $this->nextId++,
                    $total
                );
                $this->orders[] = $saved;

                return $saved;
            }

            public function findAll(): array
            {
                return $this->orders;
            }
        };
    }
}
