<?php
<<<<<<< Updated upstream
/**
 * ============================================================================
 *  CALCULO DE PRECIOS
 *  Patron esperado: STRATEGY (comportamiento)
 * ============================================================================
 */

=======
>>>>>>> Stashed changes
interface PricingStrategy
{
    public function calculate(float $amount): float;
    }
<<<<<<< Updated upstream

    final class PrivatePatientStrategy implements PricingStrategy
    {
       
        public function calculate(float $amount): float
        {
            return $amount;
        
        }
    }

    final class InsuranceStrategy implements PricingStrategy        // obra_social
{
    public function __construct(private float $discount = 0.30) {}

    public function calculate(float $amount): float
    {
        return $amount * (1 - $this->discount);
=======
    final class PrivatePatientStrategy implements PricingStrategy
    {
        public function calculate(float $amount): float
        {
            return $amount;
        }
    }

    final class InsuranceStrategy implements PricingStrategy
    {
        public function __construct(private float $discount = 0.30) {}
    
        public function calculate(float $amount): float
        {
            return $amount * (1 - $this->discount);
        }
>>>>>>> Stashed changes
    }

<<<<<<< Updated upstream
final class RetireeStrategy implements PricingStrategy          // jubilado
{
    public function calculate(float $amount): float
    {
        return $amount * 0.5;
    }
}

final class PriceCalculator
{
    public function __construct(private PricingStrategy $strategy) {}

    public function calculate(float $amount): float
    {
        return $this->strategy->calculate($amount);
    }

    /** Único lugar que conoce los tipos de paciente. */
    public static function forPatientType(string $tipoPaciente): self
    {
        return match ($tipoPaciente) {
            'obra_social' => new self(new InsuranceStrategy()),
            'jubilado'    => new self(new RetireeStrategy()),
            default       => new self(new PrivatePatientStrategy()), // 'particular' y cualquier otro
        };
    }
}
 
=======
>>>>>>> Stashed changes
