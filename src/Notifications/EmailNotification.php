<?php
/**
 * ============================================================================
 *  NOTIFICACION POR EMAIL
 * ============================================================================
 *
 *  CONTRATO UNIFICADO
 *     Esta clase implementa Notification mediante send().
 *     El destinatario se configura al construir la notificacion.
 *     El metodo unificado es send(string $message): void.
 *     Nombres distintos para la misma responsabilidad: imposible intercambiarlos.
 *
 *  IMPLEMENTACION
 *     interface Notification { public function send(string $message): void; }
 *     class EmailNotification implements Notification { public function send(...) }
 *     Mismo contrato => polimorfismo => el cliente no conoce la clase concreta.
 * ============================================================================
 */

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
