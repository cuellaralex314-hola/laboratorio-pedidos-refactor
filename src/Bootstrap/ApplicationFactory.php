<?php

final class ApplicationFactory
{
    public static function build(array $config): OrderController
    {
        $connection = Connection::getInstance($config);
        $repository = new PdoOrderRepository($connection);
        $priceCalculator = new PriceCalculator([
            'particular' => new ParticularStrategy(),
            'obra_social' => new ObraSocialStrategy(),
            'jubilado' => new JubiladoStrategy(),
            // Para agregar un tipo nuevo se registra su estrategia aquí; PriceCalculator no se modifica.
            'prepaga' => new PrepaidStrategy(),
        ]);
        $notificationSender = new NotificationSender(new NotificationFactory());
        $subject = new OrderSubject([
            new EmailObserver(),
            new SmsObserver(),
            new DashboardObserver(),
        ]);
        $events = new OrderEvents($subject);
        $service = new OrderService(
            new OrderValidator(),
            $priceCalculator,
            $repository,
            $notificationSender,
            $events
        );

        return new OrderController($service, $repository, new ReportGenerator());
    }
}
