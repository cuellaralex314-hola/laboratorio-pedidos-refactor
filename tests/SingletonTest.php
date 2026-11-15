<?php

use PHPUnit\Framework\TestCase;

final class SingletonTest extends TestCase
{
    public function testGetInstanceAndSpanishAliasReturnTheSameConnection(): void
    {
        $connection = Connection::getInstance();

        $this->assertSame($connection, Connection::getInstance());
        $this->assertSame($connection, Connection::obtener());
    }
}
