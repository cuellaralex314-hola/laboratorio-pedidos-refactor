<?php

final class SmsObserver implements OrderObserver
{
    public function update(Order $order): string
    {
        return "SmsObserver recibió el evento del pedido #{$order->getId()}.";
    }
}