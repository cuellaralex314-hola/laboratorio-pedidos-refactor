<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/autoload.php';

$passed = 0;
$failed = 0;

function test(string $name, callable $test): void
{
    global $passed, $failed;
    try {
        $test();
        $passed++;
        echo "[OK] {$name}\n";
    } catch (Throwable $exception) {
        $failed++;
        echo "[ERROR] {$name}: {$exception->getMessage()}\n";
    }
}

function same(mixed $expected, mixed $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(($message ? $message . ' — ' : '') . 'esperado ' . var_export($expected, true) . ', recibido ' . var_export($actual, true));
    }
}

function truth(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function pricingCalculator(): PriceCalculator
{
    return new PriceCalculator([
        'particular' => new ParticularStrategy(),
        'obra_social' => new ObraSocialStrategy(),
        'jubilado' => new JubiladoStrategy(),
        'prepaga' => new PrepaidStrategy(),
    ]);
}

$repository = new class implements OrderRepository {
    private array $orders = [];
    private int $nextId = 1;

    public function save(Order $order, float $total): Order
    {
        $id = $order->getId() ?? $this->nextId++;
        $saved = $order->withPersistenceData($id, $total);
        $this->orders[] = $saved;
        return $saved;
    }

    public function findAll(): array
    {
        return $this->orders;
    }
};

test('Strategy calcula los cuatro tipos de paciente', function (): void {
    $calculator = pricingCalculator();
    same(20000.0, $calculator->calculate('particular', 20000));
    same(14000.0, $calculator->calculate('obra_social', 20000));
    same(10000.0, $calculator->calculate('jubilado', 20000));
    same(16000.0, $calculator->calculate('prepaga', 20000));
});

test('PriceCalculator rechaza tipos de paciente desconocidos', function (): void {
    try {
        pricingCalculator()->calculate('tipo_inexistente', 100);
    } catch (InvalidArgumentException) {
        return;
    }
    throw new RuntimeException('Se esperaba InvalidArgumentException.');
});

test('Factory crea una notificación WhatsApp mediante el contrato común', function (): void {
    $notification = (new NotificationFactory())->create('whatsapp', '3704000000');
    truth($notification instanceof Notification, 'El objeto debe implementar Notification.');
    truth(str_contains($notification->send('Pedido listo'), 'WhatsApp simulado'), 'Debe indicar WhatsApp simulado.');
});

test('Adapter traduce send() a sendMessage()', function (): void {
    $adapter = new LegacyNotifierAdapter(new LegacyNotifier());
    same('[LEGACY simulado] Mensaje', $adapter->send('Mensaje'));
});

test('Decorator permite encadenar firma, PDF y marca de agua sin booleanos', function (): void {
    $report = new BasicReport('Prueba');
    $report = new DigitalSignatureDecorator($report);
    $report = new PdfReportDecorator($report);
    $report = new WatermarkDecorator($report);
    same('Reporte: Prueba + firma digital + PDF + marca de agua', $report->generate());
});

test('Observer notifica a todos los observadores registrados', function (): void {
    $subject = new OrderSubject([new EmailObserver(), new SmsObserver(), new DashboardObserver()]);
    $order = new Order(7, 'Paciente Prueba', 1000, 'particular', 1000);
    $messages = $subject->notify($order);
    same(3, count($messages));
    truth(str_contains($messages[1], 'SmsObserver'), 'Debe ejecutarse SmsObserver.');
});

test('Facade valida, calcula, persiste, notifica y emite evento', function () use ($repository): void {
    $subject = new OrderSubject([new EmailObserver(), new SmsObserver(), new DashboardObserver()]);
    $service = new OrderService(
        new OrderValidator(),
        pricingCalculator(),
        $repository,
        new NotificationSender(new NotificationFactory()),
        new OrderEvents($subject)
    );
    $result = $service->procesarPedidoCompleto(
        new Order(null, 'Paciente Prueba', 20000, 'obra_social'),
        'email',
        'paciente@example.com'
    );
    same(14000.0, $result['order']->getTotal());
    same(1, $result['order']->getId());
    truth(str_contains($result['notification'], 'EMAIL simulado'), 'Debe producir una notificación simulada.');
    same(3, count($result['events']));
    same(1, count($repository->findAll()));
});

test('Order rechaza paciente vacío e importe no positivo', function (): void {
    foreach ([['', 100], ['Paciente', 0], ['Paciente', -1]] as [$patient, $amount]) {
        try {
            new Order(null, $patient, (float) $amount, 'particular');
        } catch (InvalidArgumentException) {
            continue;
        }
        throw new RuntimeException('Se esperaba que los datos inválidos fueran rechazados.');
    }
});

test('ReportGenerator compone reporte diario con decoradores', function (): void {
    $order = new Order(1, 'Paciente', 1000, 'particular', 1000);
    $text = (new ReportGenerator())->generateDailyReport([$order]);
    truth(str_contains($text, 'Pedidos registrados: 1'), 'Debe incluir el número de pedidos.');
    truth(str_contains($text, '+ PDF'), 'Debe aplicar el decorador PDF.');
    truth(str_contains($text, '+ marca de agua'), 'Debe aplicar la marca de agua.');
});

echo "\nResultado: {$passed} correctas; {$failed} fallidas.\n";
exit($failed === 0 ? 0 : 1);
