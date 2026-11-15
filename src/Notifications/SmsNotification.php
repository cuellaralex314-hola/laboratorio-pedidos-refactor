<?php

final class SmsNotification implements Notification
{
    public function __construct(private string $to)
    {
        if (trim($to) === '') {
            throw new InvalidArgumentException('El destino del SMS es obligatorio.');
        }
    }

    public function send(string $message): string
    {
        return sprintf('[SMS simulado] Para %s: %s', $this->to, $message);
    }
}
