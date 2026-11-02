<?php

require_once __DIR__ . '/Notification.php';

class SmsNotification implements Notification
{
    public function __construct(private string $numero)
    {
    }

    public function send(string $message): void
    {
        echo "[SMS] a {$this->numero}: {$message}<br>";
    }
}
