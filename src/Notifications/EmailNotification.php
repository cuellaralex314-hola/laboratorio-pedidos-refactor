<?php

final class EmailNotification implements Notification
{
    public function __construct(private string $to)
    {
        if (trim($to) === '') {
            throw new InvalidArgumentException('El destinatario del email es obligatorio.');
        }
    }

    public function send(string $message): string
    {
        return sprintf('[EMAIL simulado] Para %s: %s', $this->to, $message);
    }
}
