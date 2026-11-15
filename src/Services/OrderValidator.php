<?php

final class OrderValidator
{
    public function validate(Order $order): void
    {
        if (trim($order->getPatient()) === '') {
            throw new InvalidArgumentException('El nombre del paciente es obligatorio.');
        }
        if (!is_finite($order->getAmount()) || $order->getAmount() <= 0) {
            throw new InvalidArgumentException('El importe debe ser mayor que cero.');
        }
    }
}
