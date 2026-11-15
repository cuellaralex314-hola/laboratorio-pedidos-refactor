<?php

final class NotificationFactory
{
    public function create(string $type, string $destination): Notification
    {
        return match ($type) {
            'email' => new EmailNotification($destination),
            'sms' => new SmsNotification($destination),
            'whatsapp' => new WhatsAppNotification($destination),
            default => throw new InvalidArgumentException(
                "Tipo de notificación no soportado: {$type}."
            ),
        };
    }
}
