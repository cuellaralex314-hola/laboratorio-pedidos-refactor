<?php

interface Notification
{
    /** Devuelve el resultado legible; el ejemplo simula el envío, no contacta proveedores externos. */
    public function send(string $message): string;
}
