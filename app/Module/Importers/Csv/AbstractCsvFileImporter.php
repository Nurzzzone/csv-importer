<?php

namespace App\Module\Importers\Csv;

use App\Module\Importers\FileImporterInterface;
use App\Module\Importers\ImportReport;
use Illuminate\Support\Facades\DB;

abstract class AbstractCsvFileImporter implements FileImporterInterface
{
    public function __construct(protected readonly ImportReport $importReport)
    {
    }

    public function import(iterable $importData, bool $testMode = false): void
    {
        DB::transaction(function () use ($importData, $testMode) {
            foreach ($importData as $data) {
                $this->importReport->incrementProcessed();

                if ($this->validate($data)) {
                    continue;
                }

                if ($testMode) {
                    $this->importReport->incrementTested();

                    continue;
                }

                $this->save($data);
            }
        });
    }

    public function getImportReport(): array
    {
        return $this->importReport->getSummary();
    }

    abstract protected function validate(array $record): bool;
    abstract protected function save(array $record): void;
}
