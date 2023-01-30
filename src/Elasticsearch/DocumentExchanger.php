<?php

namespace App\Elasticsearch;

use App\Elasticsearch\Converter\DocumentConverterInterface;
use Elastica\Document;
use JoliCode\Elastically\Messenger\DocumentExchangerInterface;

readonly class DocumentExchanger implements DocumentExchangerInterface
{
    public function __construct(
        /** @var DocumentConverterInterface[] */
        private iterable $converters
    ) {
    }

    public function fetchDocument(string $className, string $id): ?Document
    {
        foreach($this->converters as $converter) {
            if ($converter->supports($className)) {
                return $converter->fetchDocument($id);
            }
        }

        return null;
    }
}
