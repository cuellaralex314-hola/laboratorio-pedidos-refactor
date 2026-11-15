<?php

/** Simula una biblioteca de terceros que no podemos modificar. */
final class LegacyNotifier
{
    public function sendMessage(string $text): string
    {
        return '[LEGACY simulado] ' . $text;
    }
}
