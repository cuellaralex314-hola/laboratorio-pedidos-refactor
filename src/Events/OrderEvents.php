<?php

final class OrderEvents
{
    public function __construct(private OrderSubject $subject)
    {
    }

    /** @return list<string> */
    public function pedidoCreado(Order $order): array
    {
        return $this->subject->notify($order);
    }
}
