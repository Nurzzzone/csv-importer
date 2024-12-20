<?php

namespace App\Console\Commands;

use App\Module\Importers\FileImporterInterface;
use App\Module\Parsers\FileParserInterface;
use Illuminate\Console\Command;

final class ImportProductsCommand extends Command
{
    protected $signature = 'import:products {filepath} {--test}';
    protected $description = 'Import products from a CSV file.';
    private FileImporterInterface $importer;
    private FileParserInterface $parser;

    public function __construct(FileParserInterface $parser, FileImporterInterface $importer)
    {
        parent::__construct();

        $this->parser = $parser;
        $this->importer = $importer;
    }

    public function handle(): int
    {
        try {
            $importData = $this->parser->parse($this->argument('filepath'));
        } catch (\Exception $e) {
            $this->error("Error occurred while parsing file: {$e->getMessage()}");

            return self::FAILURE;
        }

        try {
            $this->importer->import($importData, $this->option('test'));
        } catch (\Exception $e) {
            $this->error("Error occurred while importing file: {$e->getMessage()}");

            return self::FAILURE;
        }

        $importReport = $this->importer->getImportReport();

        $this->info('Import completed.');
        $this->info("Tested: {$importReport['tested']}");
        $this->info("Successful: {$importReport['successful']}");
        $this->info("Processed: {$importReport['processed']}");
        $this->info('Failed: ' . count($importReport['failed']));

        return self::SUCCESS;
    }
}
