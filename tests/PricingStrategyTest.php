<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PricingStrategyTest extends TestCase
{
    /**
     * Construye una calculadora con las cuatro estrategias.
     */
    private function createCalculator(): PriceCalculator
    {
        return new PriceCalculator([
            'particular' => new ParticularStrategy(),
            'obra_social' => new ObraSocialStrategy(),
            'jubilado' => new JubiladoStrategy(),
            'prepaga' => new PrepaidStrategy(),
        ]);
    }

    /**
     * Particular paga el importe completo.
     */
    public function testParticularPaysFullAmount(): void
    {
        $calculator = $this->createCalculator();

        $this->assertSame(
            20000.0,
            $calculator->calculate('particular', 20000.0)
        );
    }

    /**
     * Obra social recibe un descuento del 30%.
     */
    public function testObraSocialReceives30PercentDiscount(): void
    {
        $calculator = $this->createCalculator();

        $this->assertSame(
            14000.0,
            $calculator->calculate('obra_social', 20000.0)
        );
    }

    /**
     * Jubilado recibe un descuento del 50%.
     */
    public function testJubiladoReceives50PercentDiscount(): void
    {
        $calculator = $this->createCalculator();

        $this->assertSame(
            10000.0,
            $calculator->calculate('jubilado', 20000.0)
        );
    }

    /**
     * Prepaga recibe un descuento del 20%.
     */
    public function testPrepaidReceives20PercentDiscount(): void
    {
        $calculator = $this->createCalculator();

        $this->assertSame(
            16000.0,
            $calculator->calculate('prepaga', 20000.0)
        );
    }

    /**
     * Rechaza tipos de pacientes desconocidos.
     */
    public function testRejectsUnknownPatientType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->createCalculator()->calculate(
            'tipo_inexistente',
            10000.0
        );
    }

    /**
     * Rechaza importes iguales a cero.
     */
    public function testRejectsZeroAmount(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->createCalculator()->calculate(
            'particular',
            0.0
        );
    }

    /**
     * Rechaza importes negativos.
     */
    public function testRejectsNegativeAmount(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->createCalculator()->calculate(
            'particular',
            -1000.0
        );
    }

    /**
     * Rechaza importes infinitos.
     */
    public function testRejectsNonFiniteAmount(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->createCalculator()->calculate(
            'particular',
            INF
        );
    }

    /**
     * Rechaza descuentos menores que cero.
     */
    public function testRejectsDiscountBelowZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ObraSocialStrategy(-0.01);
    }

    /**
     * Rechaza descuentos mayores que uno.
     */
    public function testRejectsDiscountAboveOne(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ObraSocialStrategy(1.01);
    }

    /**
     * Rechaza estrategias que no implementan PricingStrategy.
     */
    public function testRejectsInvalidStrategyRegistration(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PriceCalculator([
            'particular' => new stdClass(),
        ]);
    }

    /**
     * Devuelve los tipos de pacientes registrados.
     */
    public function testReturnsSupportedPatientTypes(): void
    {
        $calculator = $this->createCalculator();

        $this->assertSame(
            [
                'particular',
                'obra_social',
                'jubilado',
                'prepaga',
            ],
            $calculator->supportedTypes()
        );
    }
}
