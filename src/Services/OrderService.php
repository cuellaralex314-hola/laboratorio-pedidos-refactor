<?php

/**
 * Fachada del subsistema de pedidos: coordina validación, precios,
 * persistencia, notificaciones y eventos sin implementar cada tarea.
 */
final class OrderService
{
    public function __construct(
        private OrderValidator $validator,
        private PriceCalculator $priceCalculator,
        private OrderRepository $repository,
        private NotificationSender $notificationSender,
        private OrderEvents $events
    ) {
    }

    /** @return array{order: Order, notification: string, events: list<string>} */
    public function procesarPedidoCompleto(
        Order $order,
        string $notificationType,
        string $destination
    ): array {
        $this->validator->validate($order);
        $total = $this->priceCalculator->calculate($order->getPatientType(), $order->getAmount());
        $savedOrder = $this->repository->save($order, $total);
        $notification = $this->notificationSender->send($notificationType, $destination, $this->buildMessage($savedOrder));
        $eventMessages = $this->events->pedidoCreado($savedOrder);
        return ['order' => $savedOrder, 'notification' => $notification, 'events' => $eventMessages];
    }

    private function buildMessage(Order $order): string
    {
        return sprintf('Pedido #%d creado. Total: $ %.2f.', $order->getId(), $order->getTotal() ?? 0.0);
    }
}
