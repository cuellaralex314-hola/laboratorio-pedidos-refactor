<?php

require_once __DIR__ . '/Notification.php';
require_once __DIR__ . '/EmailNotification.php';
require_once __DIR__ . '/SmsNotification.php';
require_once __DIR__ . '/WhatsAppNotification.php';

final class NotificationFactory
{
    public static function create(
        string $type,
        string $destino
    ): Notification {

        return match ($type) {
            'email' => new EmailNotification($destino),
            'sms' => new SmsNotification($destino),
            'whatsapp' => new WhatsAppNotification($destino),

            default => throw new InvalidArgumentException(
                "Tipo de notificacion no soportado: {$type}"
            ),
        };
    }
}