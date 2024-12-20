<?php

namespace Tests\Unit\Module\Importers;

use App\Module\Importers\Csv\ProductCsvFileImporter;
use App\Module\Importers\ImportReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

final class ProductCsvFileImporterTest extends TestCase
{
    use DatabaseTransactions;

    private ProductCsvFileImporter $importer;
    private ImportReport $importReport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importReportMock = $this->createMock(ImportReport::class);
        $this->importer = new ProductCsvFileImporter($this->importReportMock);
    }

    public function testSuccessfulImport(): void
    {
        $records = [
            [
                'Product Code' => 'P0003',
                'Product Name' => 'Old VCR',
                'Product Description' => 'Vintage VCR Player',
                'Stock' => 122,
                'Cost in GBP' => 1500,
                'Discontinued' => 'yes',
            ]
        ];

        $this->importReportMock
            ->expects($this->once())
            ->method('incrementProcessed');

        $this->importReportMock
            ->expects($this->once())
            ->method('incrementSuccessful');

        $this->importer->import($records);

        $this->assertDatabaseHas('tblProductData', [
            'strProductName' => $records[0]['Product Name'],
            'strProductCode' => $records[0]['Product Code'],
            'strProductDesc' => $records[0]['Product Description'],
            'dtmDiscontinued' => now(),
            'dtmAdded' => now(),
            'decCost' => $records[0]['Cost in GBP'],
            'intStock' => $records[0]['Stock'],
        ]);
    }

    public function testFailedCostRangeOver(): void
    {
        $records = [
            [
                'Product Code' => 'P0003',
                'Product Name' => 'Old VCR',
                'Product Description' => 'Vintage VCR Player',
                'Stock' => 122,
                'Cost in GBP' => 1500,
                'Discontinued' => null,
            ]
        ];

        $this->importReportMock
            ->expects($this->once())
            ->method('addFailed')
            ->with($records[0], 'Price is out of range');

        $this->importer->import($records);
    }

    public function testFailedCostRangeLess()
    {
        $records = [
            [
                'Product Code' => 'P0003',
                'Product Name' => 'Old VCR',
                'Product Description' => 'Vintage VCR Player',
                'Stock' => 122,
                'Cost in GBP' => 3.44,
                'Discontinued' => null,
            ]
        ];

        $this->importReportMock
            ->expects($this->once())
            ->method('addFailed')
            ->with($records[0], 'Price is out of range');

        $this->importer->import($records);
    }

    public function testFailedQuantityRange(): void
    {
        $records = [
            [
                'Product Code' => 'P0003',
                'Product Name' => 'Old VCR',
                'Product Description' => 'Vintage VCR Player',
                'Stock' => 5,
                'Cost in GBP' => 50.00,
                'Discontinued' => null,
            ]
        ];

        $this->importReportMock
            ->expects($this->once())
            ->method('addFailed')
            ->with($records[0], 'Quantity is out of range');

        $this->importer->import($records);
    }
}
