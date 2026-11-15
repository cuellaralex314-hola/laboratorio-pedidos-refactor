<?php

final class NotificationSender
{
    public function __construct(private NotificationFactory $factory)
    {
    }

    public function send(string $type, string $destination, string $message): string
    {
        return $this->factory->create($type, $destination)->send($message);
    }
}
