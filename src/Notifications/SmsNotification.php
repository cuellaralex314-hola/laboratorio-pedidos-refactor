<?php
/**
 * ============================================================================
 *  NOTIFICACION POR SMS
 * ============================================================================
 *
 *  CONTRATO UNIFICADO
 *     Misma responsabilidad que EmailNotification mediante send().
 *     El numero se configura al construir la notificacion.
 *
 *  IMPLEMENTACION
 *     Ambas implementan Notification::send(string $message): void.
 * ============================================================================
 */

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
