<?php

final class PriceCalculator
{
    /** @param array<string, PricingStrategy> $strategies Registro de estrategias por tipo de paciente. */
    public function __construct(private array $strategies)
    {
        foreach ($this->strategies as $type => $strategy) {
            if (!is_string($type) || !$strategy instanceof PricingStrategy) {
                throw new InvalidArgumentException('Cada estrategia debe asociarse a un tipo y cumplir PricingStrategy.');
            }
        }
    }

    public function calculate(string $patientType, float $amount): float
    {
        if (!isset($this->strategies[$patientType])) {
            throw new InvalidArgumentException("Tipo de paciente no soportado: {$patientType}.");
        }
        if (!is_finite($amount) || $amount <= 0) {
            throw new InvalidArgumentException('El importe debe ser mayor que cero.');
        }

        return round($this->strategies[$patientType]->calculate($amount), 2);
    }

    /** @return list<string> */
    public function supportedTypes(): array
    {
        return array_keys($this->strategies);
    }
}
