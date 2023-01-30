<?php

namespace App\Elasticsearch\Converter;

use Elastica\Document;

interface DocumentConverterInterface
{
    public function supports(string $className): bool;

    public function fetchDocument(string $id): ?Document;
}
