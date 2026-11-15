<?php

final class ObraSocialStrategy extends DiscountPricingStrategy
{
    public function __construct(float $discount = 0.30)
    {
        parent::__construct($discount);
    }
}