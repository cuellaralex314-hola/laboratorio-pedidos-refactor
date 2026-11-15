<?php

abstract class DiscountPricingStrategy implements PricingStrategy
{
    public function __construct(protected float $discount)
    {
        if ($discount < 0 || $discount > 1) {
            throw new InvalidArgumentException('El descuento debe estar entre 0 y 1.');
        }
    }

    public function calculate(float $amount): float
    {
        return round($amount * (1 - $this->discount), 2);
    }
}