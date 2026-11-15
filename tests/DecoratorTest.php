<?php

use PHPUnit\Framework\TestCase;

final class DecoratorTest extends TestCase
{
    public function testDecoratorsCanBeComposedAroundAReport(): void
    {
        $report = new BasicReport('Pedidos del día');
        $report = new DigitalSignatureDecorator($report);
        $report = new PdfReportDecorator($report);
        $report = new WatermarkDecorator($report);

        $this->assertSame(
            'Reporte: Pedidos del día + firma digital + PDF + marca de agua',
            $report->generate()
        );
    }

    public function testReportGeneratorAddsDecoratorsToDailyReport(): void
    {
        $report = (new ReportGenerator())->generateDailyReport([
            new Order(1, 'Paciente', 1500.0, 'particular', 1500.0),
        ]);

        $this->assertStringContainsString('Pedidos registrados: 1', $report);
        $this->assertStringContainsString('Total acumulado: $ 1500.00', $report);
        $this->assertStringContainsString('+ firma digital + PDF + marca de agua', $report);
    }
}
