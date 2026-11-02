<?php
final class BasicReport implements Report
{
    public function __construct(private string $contenido)
    {
    }

    public function generate(): string
    {
        return "Reporte: {$this->contenido}";
    }
}

abstract class ReportDecorator implements Report
{
    public function __construct(protected Report $report)
    {
    }
}

final class DigitalSignatureDecorator extends ReportDecorator
{
    public function generate(): string
    {
        return $this->report->generate() . ' + firma digital';
    }
}

final class PdfReportDecorator extends ReportDecorator
{
    public function generate(): string
    {
        return $this->report->generate() . ' + PDF';
    }
}

final class WatermarkDecorator extends ReportDecorator
{
    public function generate(): string
    {
        return $this->report->generate() . ' + marca de agua';
    }
}

class ReportGenerator
{
    public function generate(
        string $contenido,
        bool $conFirmaDigital = false,
        bool $conPdf = false,
        bool $conMarcaDeAgua = false
    ): string {
        $reporte = new BasicReport($contenido);

        if ($conFirmaDigital) {
            $reporte = new DigitalSignatureDecorator($reporte);
        }

        if ($conPdf) {
            $reporte = new PdfReportDecorator($reporte);
        }

        if ($conMarcaDeAgua) {
            $reporte = new WatermarkDecorator($reporte);
        }

        return $reporte->generate();
    }
}
