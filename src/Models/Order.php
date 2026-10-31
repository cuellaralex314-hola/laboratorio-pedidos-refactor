<?php

class Order
{
    public readonly int $id;
    public readonly string $patient;
    public readonly float $amount;
    public readonly string $patientType;

    public function __construct(int $id, string $patient, float $amount, string $patientType)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El id del pedido debe ser mayor que cero.');
        }

        if (trim($patient) === '') {
            throw new InvalidArgumentException('El paciente es obligatorio.');
        }

        if (!is_finite($amount) || $amount <= 0) {
            throw new InvalidArgumentException('El monto debe ser un número finito mayor que cero.');
        }

        if (trim($patientType) === '') {
            throw new InvalidArgumentException('El tipo de paciente es obligatorio.');
        }

        $this->id = $id;
        $this->patient = $patient;
        $this->amount = $amount;
        $this->patientType = $patientType;
    }

    public function guardar(): void
    {
        (new OrderRepository(Connection::obtener()))->save($this);
    }
}

class OrderRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(Order $order): void
    {
        $patient = str_replace("'", "''", $order->patient);
        $amount = (string) $order->amount;

        $this->connection->ejecutar(
            "INSERT INTO orders (id, patient, amount) VALUES ({$order->id}, '{$patient}', {$amount})"
        );
    }
}
