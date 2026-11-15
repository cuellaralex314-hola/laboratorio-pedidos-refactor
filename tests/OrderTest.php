<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testRejectsEmptyPatient(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Order(
            null,
            '',
            1000.0,
            'particular'
        );
    }

    public function testRejectsZeroAmount(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Order(
            null,
            'Juan Perez',
            0.0,
            'particular'
        );
    }

    public function testCreatesOrderWithValidData(): void
    {
        $order = new Order(
            null,
            'Juan Perez',
            15000.0,
            'obra_social'
        );

        $this->assertSame('Juan Perez', $order->getPatient());
        $this->assertSame(15000.0, $order->getAmount());
        $this->assertSame('obra_social', $order->getPatientType());
    }
}
