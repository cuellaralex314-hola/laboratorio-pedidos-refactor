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
        private NotificationSender $notificationSender,
        private OrderEvents $events
    ) {
    }
        public function __construct(private OrderEvents $eventos) {}
    /**
     * ❌ METODO MAL APLICADO: procesarPedidoCompleto()
     *    Sintomas medibles:
     *      - mas de 6 responsabilidades en un solo metodo
     *      - 4 niveles de anidamiento
     *      - imposible de probar sin base de datos ni servidor de mail
     *      - imprime HTML desde la capa de servicio (rompe MVC)
     */
    public function procesarPedidoCompleto(
        int $id,
        string $paciente,
        float $monto,
        string $tipoPaciente,
        string $tipoNotificacion,
        string $destino
    ): void {
        // ---- 1. Validacion (deberia estar en su propio validador) ----
        if (trim($paciente) === '') {
            echo 'Falta el paciente<br>';   // ❌ la capa de servicio imprime HTML
            return;
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
        if ($monto <= 0) {
            echo 'Monto invalido<br>';
            return;
        }

        // ---- 2. Calculo de precio (deberia ser una Strategy) ----
        $total = $monto;

        if ($tipoPaciente === 'obra_social') {
            $total = $monto * 0.7;          // ❌ tercera copia del 0.7 en el proyecto
        } elseif ($tipoPaciente === 'jubilado') {
            $total = $monto * 0.5;
        }

        // ---- 3. Persistencia (deberia ser un Repository) ----
        // ❌ new adentro del metodo: no se puede reemplazar por un doble en pruebas
        $order = new Order($id, $paciente, $total, $tipoPaciente);
        $order->guardar();

        // ---- 4. Notificacion (deberia ser una Factory) ----
        // ❌ MISMO if que ya existe en NotificationSender::enviar()
        if ($tipoNotificacion === 'email') {
            $email = new EmailNotification();
            $email->enviarEmail($destino, 'Pedido creado', "Pedido {$id} por $ {$total}");
        }

        if ($tipoNotificacion === 'sms') {
            $sms = new SmsNotification();
            $sms->mandarSms($destino, "Pedido {$id} por $ {$total}");
        }

        // ---- 5. Avisos internos (deberia ser un Observer) ----
                $this->eventos->pedidoCreado($order);
        // ---- 6. Reporte (deberia ser un Decorator) ----
        $generador = new ReportGenerator();
        echo $generador->generate("Pedido {$id}", true, true, false) . '<br>';

        // ---- 7. Presentacion (deberia ser una View) ----
        echo "<p>Pedido {$id} procesado. Total: $ {$total}</p>";
    }
}
