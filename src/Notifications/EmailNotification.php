<?php

require_once __DIR__ . '/Notification.php';

class EmailNotification implements Notification
{
    public function __construct(
        private string $destinatario,
        private string $asunto = 'Pedido del laboratorio'
    ) {
    }

    public function send(string $message): void
    {
        echo "[EMAIL] para {$this->destinatario} | {$this->asunto}: {$message}<br>";
    }
}
