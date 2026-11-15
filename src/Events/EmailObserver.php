<?php

final class EmailObserver implements OrderObserver
{
    public function update(Order $order): string
    {
        return "EmailObserver recibió el evento del pedido #{$order->getId()}.";
    }
}