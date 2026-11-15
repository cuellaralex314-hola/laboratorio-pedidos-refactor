<?php

final class BasicReport implements Report
{
    public function __construct(private string $content)
    {
    }

    public function generate(): string
    {
        return 'Reporte: ' . $this->content;
    }
}