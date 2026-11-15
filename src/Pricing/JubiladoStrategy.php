<?php

final class JubiladoStrategy extends DiscountPricingStrategy
{
    public function __construct(float $discount = 0.50)
    {
        parent::__construct($discount);
    }
}