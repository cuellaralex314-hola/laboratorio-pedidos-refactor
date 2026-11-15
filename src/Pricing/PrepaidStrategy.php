<?php

final class PrepaidStrategy extends DiscountPricingStrategy
{
    public function __construct(float $discount = 0.20)
    {
        parent::__construct($discount);
    }
}