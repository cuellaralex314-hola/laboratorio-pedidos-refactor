<?php

use PHPUnit\Framework\TestCase;

final class ObserverTest extends TestCase
{
    public function testSubjectNotifiesObserversInSubscriptionOrder(): void
    {
        $subject = new OrderSubject();
        $subject->subscribe($this->observerReturning('first'));
        $subject->subscribe($this->observerReturning('second'));

        $messages = $subject->notify(
            new Order(7, 'Paciente', 1000.0, 'particular')
        );

        $this->assertSame(['first', 'second'], $messages);
    }

    public function testUnsubscribeRemovesOnlyTheSpecifiedObserver(): void
    {
        $removed = $this->observerReturning('removed');
        $remaining = $this->observerReturning('remaining');
        $subject = new OrderSubject([$removed, $remaining]);

        $subject->unsubscribe($removed);

        $this->assertSame(
            ['remaining'],
            $subject->notify(new Order(7, 'Paciente', 1000.0, 'particular'))
        );
    }

    private function observerReturning(string $message): OrderObserver
    {
        return new class($message) implements OrderObserver {
            public function __construct(private string $message)
            {
            }

            public function update(Order $order): string
            {
                return $this->message;
            }
        };
    }
}
