<?php

final class PdoOrderRepository implements OrderRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(Order $order, float $total): Order
    {
        $pdo = $this->connection->pdo();

        if ($order->getId() === null) {
            $statement = $pdo->prepare(
                'INSERT INTO orders (patient, amount, patient_type, total) '
                . 'VALUES (:patient, :amount, :patient_type, :total)'
            );
            $statement->execute([
                'patient' => $order->getPatient(),
                'amount' => $order->getAmount(),
                'patient_type' => $order->getPatientType(),
                'total' => $total,
            ]);
            $id = (int) $pdo->lastInsertId();
        } else {
            $id = $order->getId();
            $statement = $pdo->prepare(
                'UPDATE orders SET patient = :patient, amount = :amount, '
                . 'patient_type = :patient_type, total = :total WHERE id = :id'
            );
            $statement->execute([
                'id' => $id,
                'patient' => $order->getPatient(),
                'amount' => $order->getAmount(),
                'patient_type' => $order->getPatientType(),
                'total' => $total,
            ]);

            if ($statement->rowCount() === 0 && !$this->exists($id)) {
                throw new RuntimeException("No existe el pedido #{$id} que intentás actualizar.");
            }
        }

        return $order->withPersistenceData($id, $total);
    }

    public function findAll(): array
    {
        $statement = $this->connection->pdo()->query(
            'SELECT id, patient, amount, patient_type, total FROM orders ORDER BY id DESC'
        );
        $orders = [];

        foreach ($statement->fetchAll() as $row) {
            $orders[] = new Order(
                (int) $row['id'],
                (string) $row['patient'],
                (float) $row['amount'],
                (string) $row['patient_type'],
                (float) $row['total']
            );
        }

        return $orders;
    }

    private function exists(int $id): bool
    {
        $statement = $this->connection->pdo()->prepare('SELECT 1 FROM orders WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetchColumn() !== false;
    }
}