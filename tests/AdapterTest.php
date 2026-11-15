<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AdapterTest extends TestCase
{
    /**
     * Comprueba que el adaptador implemente
     * el contrato Notification.
     */
    public function testAdapterImplementsNotificationInterface(): void
    {
        $adapter = new LegacyNotifierAdapter(
            new LegacyNotifier()
        );

        $this->assertInstanceOf(
            Notification::class,
            $adapter
        );
    }

    /**
     * Comprueba que el adaptador traduzca
     * send() a sendMessage().
     */
    public function testAdapterDelegatesMessageToLegacyNotifier(): void
    {
        $adapter = new LegacyNotifierAdapter(
            new LegacyNotifier()
        );

        $result = $adapter->send('Pedido listo');

        $this->assertSame(
            '[LEGACY simulado] Pedido listo',
            $result
        );
    }

    /**
     * Comprueba que el adaptador devuelva
     * el resultado generado por la clase antigua.
     */
    public function testAdapterReturnsLegacyNotifierResult(): void
    {
        $legacy = new LegacyNotifier();

        $adapter = new LegacyNotifierAdapter($legacy);

        $result = $adapter->send('Mensaje de prueba');

        $this->assertSame(
            $legacy->sendMessage('Mensaje de prueba'),
            $result
        );
    }
}
