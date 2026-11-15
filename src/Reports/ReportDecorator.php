<?php

abstract class ReportDecorator implements Report
{
    public function __construct(protected Report $report)
    {
    }
}