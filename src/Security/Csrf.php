<?php

final class Csrf
{
    public static function token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new RuntimeException('La sesión debe iniciarse antes de generar el token CSRF.');
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['_csrf_token'];
    }

    public static function assertValid(string $token): void
    {
        $expected = $_SESSION['_csrf_token'] ?? '';
        if (!is_string($expected) || $expected === '' || !hash_equals($expected, $token)) {
            throw new InvalidArgumentException('El formulario venció o el token de seguridad no es válido. Volvé a cargarlo e intentá nuevamente.');
        }
    }
}