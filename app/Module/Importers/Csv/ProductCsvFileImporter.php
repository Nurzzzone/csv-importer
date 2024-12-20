<?php

namespace App\Module\Importers\Csv;

use Carbon\Carbon;
use App\Models\Product;

class ProductCsvFileImporter extends AbstractCsvFileImporter
{
    public function validate(array $record): bool
    {
        if (empty($record['Product Name']) || empty($record['Product Code'] || empty($record['Stock']) || empty($record['Cost in GBP']))) {
            $this->importReport->addFailed($record, 'Missing required fields');

            return false;
        }

        $price = floatval(str_replace(['$', ','], '', $record['Cost in GBP']));

        if ($price < 5 || $price > 1000) {
            $this->importReport->addFailed($record, 'Price is out of range');

            return false;
        }

        if (intval($record['Stock']) < 10) {
            $this->importReport->addFailed($record, 'Quantity is out of range');

            return false;
        }

        return true;
    }

    public function save(array $record): void
    {
        try {
            Product::query()->create([
                'strProductName' => $record['Product Name'],
                'strProductDesc' => $record['Product Description'],
                'strProductCode' => $record['Product Code'],
                'decCost' => $record['Cost in GBP'],
                'intStock' => $record['Stock'],
                'dtmAdded' => Carbon::now(),
                'dtmDiscontinued' => $record['Discontinued'] === 'yes' ? now() : null,
            ]);

            $this->importReport->incrementSuccessful();
        } catch (\Exception $e) {
            $this->importReport->addFailed($record, $e->getMessage());
        }
    }
}
