<?php

interface OrderRepository
{
    /** Persiste el pedido y devuelve la entidad con ID y total calculado. */
    public function save(Order $order, float $total): Order;

    /** @return list<Order> */
    public function findAll(): array;
}