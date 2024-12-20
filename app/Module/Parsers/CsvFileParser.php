<?php

namespace App\Module\Parsers;

class CsvFileParser implements FileParserInterface
{
    public function parse(string $filepath): \Generator
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw new \RuntimeException("File not found or unreadable: $filepath");
        }

        if (($file = fopen($filepath, 'r')) === false) {
            throw new \RuntimeException("Unable to open file: $filepath");
        }

        try {
            $headers = fgetcsv($file);

            while (($row = fgetcsv($file, 1000)) !== false) {
                if ((count($headers) != count($row) || empty($row))) {
                    continue;
                }

                yield array_combine($headers, $row);
            }
        } finally {
            fclose($file);
        }
    }
}
