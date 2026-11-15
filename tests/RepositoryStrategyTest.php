<?php

use PHPUnit\Framework\TestCase;

final class RepositoryStrategyTest extends TestCase
{
    private PDO $pdo;

    private PdoOrderRepository $repository;

    protected function setUp(): void
    {
        if (!in_array('sqlite', PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('La extensión PDO SQLite no está disponible.');
        }

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec(
            'CREATE TABLE orders ('
            . 'id INTEGER PRIMARY KEY AUTOINCREMENT, '
            . 'patient VARCHAR(150) NOT NULL, '
            . 'amount DECIMAL(12,2) NOT NULL, '
            . 'patient_type VARCHAR(30) NOT NULL, '
            . 'total DECIMAL(12,2) NOT NULL'
            . ')'
        );

        $connection = Connection::getInstance();
        $pdoProperty = new ReflectionProperty(Connection::class, 'pdo');
        $pdoProperty->setValue($connection, $this->pdo);
        $this->repository = new PdoOrderRepository($connection);
    }

    public function testSaveAndFindAllRoundTripOrderData(): void
    {
        $saved = $this->repository->save(
            new Order(null, 'Paciente', 20000.0, 'obra_social'),
            14000.0
        );

        $this->assertSame(1, $saved->getId());
        $this->assertSame(14000.0, $saved->getTotal());
        $this->assertEquals(
            [$saved],
            $this->repository->findAll()
        );
    }

    public function testUpdatePersistsChangedOrderData(): void
    {
        $this->repository->save(
            new Order(null, 'Paciente', 20000.0, 'obra_social'),
            14000.0
        );

        $updated = $this->repository->save(
            new Order(1, 'Paciente actualizado', 10000.0, 'particular'),
            10000.0
        );

        $this->assertSame('Paciente actualizado', $updated->getPatient());
        $this->assertSame(10000.0, $this->repository->findAll()[0]->getTotal());
    }

    public function testUpdateOfMissingOrderFailsExplicitly(): void
    {
        $this->expectException(RuntimeException::class);

        $this->repository->save(
            new Order(99, 'Paciente', 1000.0, 'particular'),
            1000.0
        );
    }
}
