<?php

namespace App\Module\Importers;

interface FileImporterInterface
{
    public function import(iterable $importData, bool $testMode = false): void;

    public function getImportReport(): array;
}
