<?php

final class ReportGenerator
{
    public function generate(Report $report): string
    {
        return $report->generate();
    }

    /** Crea un reporte diario encadenando decoradores, sin banderas booleanas. */
    public function generateDailyReport(array $orders): string
    {
        $total = array_reduce(
            $orders,
            static fn (float $sum, Order $order): float => $sum + ($order->getTotal() ?? $order->getAmount()),
            0.0
        );
        $report = new BasicReport(sprintf(
            'Pedidos registrados: %d. Total acumulado: $ %.2f. Fecha: %s',
            count($orders),
            $total,
            date('d/m/Y H:i')
        ));
        $report = new DigitalSignatureDecorator($report);
        $report = new PdfReportDecorator($report);
        $report = new WatermarkDecorator($report);

        return $this->generate($report);
    }
}
