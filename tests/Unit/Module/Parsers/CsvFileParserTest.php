<?php

namespace Tests\Unit\Module\Parsers;

use Tests\TestCase;
use App\Module\Parsers\CsvFileParser;

final class CsvFileParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new CsvFileParser();
    }

    public function testParseSuccessful(): void
    {
        $content = <<<CSV
        Product Code,Product Name,Stock,Price,Discontinued
        P0001,Item 1,10,20.00,
        P0002,Item 2,5,15.50,yes
        P0003,Item 3,0,100.00,
        CSV;

        $expected = [
            [
                'Product Code' => 'P0001',
                'Product Name' => 'Item 1',
                'Stock' => '10',
                'Price' => '20.00',
                'Discontinued' => '',
            ],
            [
                'Product Code' => 'P0002',
                'Product Name' => 'Item 2',
                'Stock' => '5',
                'Price' => '15.50',
                'Discontinued' => 'yes',
            ],
            [
                'Product Code' => 'P0003',
                'Product Name' => 'Item 3',
                'Stock' => '0',
                'Price' => '100.00',
                'Discontinued' => '',
            ],
        ];

        $file = $this->createTempCsvFile($content);

        try {
            $result = iterator_to_array($this->parser->parse($file));

            $this->assertEquals($expected, $result);
        } finally {
            unlink($file);
        }
    }

    public function testFailedWithNonExistingFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('File not found or unreadable');

        iterator_to_array($this->parser->parse('/path/to/nonexistent/file.csv'));
    }

    public function testFailedWithEmptyHeaders(): void
    {
        $content = <<<CSV

        P0001,Item 1,10,20.00
        P0002,Item 2,5,15.50
        CSV;

        $file = $this->createTempCsvFile($content);

        try {
            $result = iterator_to_array($this->parser->parse($file));

            $this->assertEmpty($result);
        } finally {
            unlink($file);
        }
    }

    public function testFailedWithMismatchingColumns(): void
    {
        $expected = [
            ['Product Code' => 'P0001', 'Product Name' => 'Item 1', 'Stock' => '10', 'Price' => '20.00'],
        ];

        $content = <<<CSV
        Product Code,Product Name,Stock,Price
        P0001,Item 1,10,20.00
        P0002,Item 2,5
        CSV;

        $file = $this->createTempCsvFile($content);

        try {
            $result = iterator_to_array($this->parser->parse($file));

            $this->assertEquals($expected, $result);

        } finally {
            unlink($file);
        }
    }

    private function createTempCsvFile(string $content): string
    {
        $filepath = tempnam(sys_get_temp_dir(), 'csv_test_');
        file_put_contents($filepath, $content);

        return $filepath;
    }
}
