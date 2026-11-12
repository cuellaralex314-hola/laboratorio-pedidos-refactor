<?php

/**
 * Fachada del subsistema de pedidos: coordina validación, precios,
 * persistencia, notificaciones y eventos sin implementar cada tarea.
 */
final class OrderService
{
    public function __construct(
        private OrderValidator $validator,
        private OrderRepository $repository,
        private NotificationSender $notificationSender,
        private OrderEvents $events
    ) {
    }

    public function procesarPedidoCompleto(
        Order $order,
        string $notificationType,
        string $destination
    ): void {
        $this->validator->validate($order);
        $total = PriceCalculator::forPatientType($order->getPatientType())
            ->calculate($order->getAmount());

        $this->repository->save($order);
        $this->notificationSender->enviar(
            $notificationType,
            $destination,
            sprintf('Pedido #%d creado. Total: $ %.2f.', $order->getId(), $total)
        );
        $this->events->pedidoCreado($order);
    }
}