<?php

final class OrderSubject
{
    /** @var list<OrderObserver> */
    private array $observers = [];

    /** @param list<OrderObserver> $observers */
    public function __construct(array $observers = [])
    {
        foreach ($observers as $observer) {
            $this->subscribe($observer);
        }
    }

    public function subscribe(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function unsubscribe(OrderObserver $observer): void
    {
        $this->observers = array_values(array_filter(
            $this->observers,
            static fn (OrderObserver $current): bool => $current !== $observer
        ));
    }

    /** @return list<string> */
    public function notify(Order $order): array
    {
        $messages = [];
        foreach ($this->observers as $observer) {
            $messages[] = $observer->update($order);
        }
        return $messages;
    }
}