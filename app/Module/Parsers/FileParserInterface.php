<?php

namespace App\Module\Parsers;

interface FileParserInterface
{
    public function parse(string $filepath): \Generator;
}
