<?php

namespace App\Elasticsearch\Repository;

use JoliCode\Elastically\Client;
use JoliCode\Elastically\Index;

readonly class AbstractRepository
{
    protected Index $index;

    public function __construct(
        Client $client,
        string $indexName
    ) {
        $this->index = $client->getIndex($indexName);
    }
}
