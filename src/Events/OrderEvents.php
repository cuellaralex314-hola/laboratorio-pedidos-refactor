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

 
 
