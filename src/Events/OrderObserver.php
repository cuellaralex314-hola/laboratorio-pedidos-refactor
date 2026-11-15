<?php

interface OrderObserver
{
    public function update(Order $order): string;
}