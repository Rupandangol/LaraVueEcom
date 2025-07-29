<?php

namespace App\Interfaces;

interface ReportGeneratorInterface
{
    public function generate(string $tableName, array $fields);
}
