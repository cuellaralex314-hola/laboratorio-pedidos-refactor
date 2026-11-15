<?php

/** Adapta sendMessage() al contrato Notification::send(). */
final class LegacyNotifierAdapter implements Notification
{
    public function __construct(private LegacyNotifier $legacy)
    {
    }

    public function send(string $message): string
    {
        return $this->legacy->sendMessage($message);
    }
}