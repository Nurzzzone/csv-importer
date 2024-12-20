<?php

namespace App\Module\Importers;

class ImportReport
{
    private int $processed = 0;
    private int $successful = 0;

    private int $tested = 0;

    private array $failed = [];

    public function incrementProcessed(): void
    {
        $this->processed++;
    }

    public function incrementSuccessful(): void
    {
        $this->successful++;
    }

    public function incrementTested(): void
    {
        $this->tested++;
    }

    public function addFailed(array $record, string $message): void
    {
        $this->failed[] = ['record' => $record, 'message' => $message];
    }

    public function getSummary(): array
    {
        return [
            'processed' => $this->processed,
            'successful' => $this->successful,
            'tested' => $this->tested,
            'failed' => $this->failed,
        ];
    }
}
