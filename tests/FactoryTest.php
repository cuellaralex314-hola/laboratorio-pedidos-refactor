<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/autoload.php';

final class FactoryTest extends TestCase
{
    /**
     * Comprueba que Factory cree correctamente
     * una notificación por email.
     */
    public function testCreatesEmailNotification(): void
    {
        $factory = new NotificationFactory();

        $notification = $factory->create(
            'email',
            'usuario@example.com'
        );

        $this->assertInstanceOf(
            EmailNotification::class,
            $notification
        );

        $this->assertInstanceOf(
            Notification::class,
            $notification
        );

        $this->assertSame(
            '[EMAIL simulado] Para usuario@example.com: Pedido listo',
            $notification->send('Pedido listo')
        );
    }

    /**
     * Comprueba que Factory cree correctamente
     * una notificación por SMS.
     */
    public function testCreatesSmsNotification(): void
    {
        $factory = new NotificationFactory();

        $notification = $factory->create(
            'sms',
            '3704000000'
        );

        $this->assertInstanceOf(
            SmsNotification::class,
            $notification
        );

        $this->assertInstanceOf(
            Notification::class,
            $notification
        );

        $this->assertSame(
            '[SMS simulado] Para 3704000000: Pedido listo',
            $notification->send('Pedido listo')
        );
    }

    /**
     * Comprueba que Factory cree correctamente
     * una notificación por WhatsApp.
     */
    public function testCreatesWhatsAppNotification(): void
    {
        $factory = new NotificationFactory();

        $notification = $factory->create(
            'whatsapp',
            '3704000000'
        );

        $this->assertInstanceOf(
            WhatsAppNotification::class,
            $notification
        );

        $this->assertInstanceOf(
            Notification::class,
            $notification
        );

        $this->assertSame(
            '[WhatsApp simulado] Para 3704000000: Pedido listo',
            $notification->send('Pedido listo')
        );
    }

    /**
     * Comprueba que Factory rechace
     * los tipos de notificación desconocidos.
     */
    public function testRejectsUnknownNotificationType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new NotificationFactory();

        $factory->create(
            'telegram',
            '3704000000'
        );
    }

    /**
     * Comprueba que email rechace
     * un destinatario vacío.
     */
    public function testRejectsEmptyEmailDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new NotificationFactory();

        $factory->create('email', '');
    }

    /**
     * Comprueba que SMS rechace
     * un destino vacío.
     */
    public function testRejectsEmptySmsDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new NotificationFactory();

        $factory->create('sms', '');
    }

    /**
     * Comprueba que WhatsApp rechace
     * un destino vacío.
     */
    public function testRejectsEmptyWhatsAppDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new NotificationFactory();

        $factory->create('whatsapp', '');
    }
}
