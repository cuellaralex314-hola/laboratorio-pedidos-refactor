<?php

final class DashboardObserver implements OrderObserver
{
    public function update(Order $order): string
    {
        return "DashboardObserver actualizó el estado del pedido #{$order->getId()}.";
    }
}