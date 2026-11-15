<?php

final class Order
{
    public function __construct(
        private ?int $id,
        private string $patient,
        private float $amount,
        private string $patientType,
        private ?float $total = null
    ) {
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException('El identificador del pedido debe ser positivo.');
        }
        if (trim($patient) === '') {
            throw new InvalidArgumentException('El nombre del paciente es obligatorio.');
        }
        if (!is_finite($amount) || $amount <= 0) {
            throw new InvalidArgumentException('El importe debe ser un número mayor que cero.');
        }
        if (trim($patientType) === '') {
            throw new InvalidArgumentException('Debés seleccionar un tipo de paciente.');
        }

        $this->patient = trim($patient);
        $this->patientType = trim($patientType);
    }

    public static function fromArray(array $data): self
    {
        $idValue = $data['id'] ?? null;
        $id = ($idValue === null || $idValue === '') ? null : (int) $idValue;

        return new self(
            $id,
            (string) ($data['paciente'] ?? $data['patient'] ?? ''),
            (float) ($data['monto'] ?? $data['amount'] ?? 0),
            (string) ($data['tipo'] ?? $data['patient_type'] ?? 'particular'),
            isset($data['total']) ? (float) $data['total'] : null
        );
    }

    public function getId(): ?int { return $this->id; }
    public function getPatient(): string { return $this->patient; }
    public function getAmount(): float { return $this->amount; }
    public function getPatientType(): string { return $this->patientType; }
    public function getTotal(): ?float { return $this->total; }

    /** Devuelve una entidad nueva con el identificador y el total persistidos. */
    public function withPersistenceData(int $id, float $total): self
    {
        return new self($id, $this->patient, $this->amount, $this->patientType, $total);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'paciente' => $this->patient,
            'monto' => $this->amount,
            'tipo' => $this->patientType,
            'total' => $this->total ?? $this->amount,
        ];
    }
}
