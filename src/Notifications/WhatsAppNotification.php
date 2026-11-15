<?php

final class WhatsAppNotification implements Notification
{
    public function __construct(private string $to)
    {
        if (trim($to) === '') {
            throw new InvalidArgumentException('El destino de WhatsApp es obligatorio.');
        }
    }

    public function send(string $message): string
    {
        return sprintf('[WhatsApp simulado] Para %s: %s', $this->to, $message);
    }
}
