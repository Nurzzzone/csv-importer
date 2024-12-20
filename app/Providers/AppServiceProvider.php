<?php

namespace App\Providers;

use App\Module\Importers\Csv\ProductCsvFileImporter;
use App\Module\Importers\FileImporterInterface;
use App\Module\Parsers\CsvFileParser;
use App\Module\Parsers\FileParserInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileImporterInterface::class, ProductCsvFileImporter::class);
        $this->app->singleton(FileParserInterface::class, CsvFileParser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
