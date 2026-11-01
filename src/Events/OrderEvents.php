<?php

interface OrderObserver
{
    public function update(Order $order): void;
}

final class EmailObserver implements OrderObserver
{
    public function __construct(private EmailNotification $email, private string $destino) {}

    public function update(Order $order): void
    {
        $this->email->send("Pedido {$order->id} creado para {$this->destino}");
    }
}

final class SmsObserver implements OrderObserver
{
    public function __construct(private SmsNotification $sms, private string $numero) {}

    public function update(Order $order): void
    {
        $this->sms->send("Pedido {$order->id} creado para {$this->numero}");
    }
}

final class DashboardObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        echo "[DASHBOARD] actualizado para el pedido {$order->id}<br>";
    }
}

final class OrderEvents   // el sujeto: conoce la interfaz, no las clases concretas
{
    /** @var OrderObserver[] */
    private array $observers = [];

    public function subscribe(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function pedidoCreado(Order $order): void
    {
        foreach ($this->observers as $observer) {
            try {
                $observer->update($order);
            } catch (Throwable $e) {
                // Un observador que falla no corta a los demás.
                error_log('Observer ' . $observer::class . ' fallo: ' . $e->getMessage());
            }
        }
    }
}
 