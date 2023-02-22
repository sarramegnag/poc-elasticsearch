<?php

namespace App\Elasticsearch\Repository;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Result;
use JoliCode\Elastically\Client;

readonly class BookRepository extends AbstractRepository
{
    private const MAX_ITEMS_PER_PAGE = 20;

    public function __construct(Client $client)
    {
        parent::__construct($client, 'books');
    }

    /**
     * @return Result[]
     */
    public function findAll(?string $query = null): array
    {
        $searchQuery = $this->buildSearchQuery($query);

        $query = (new Query())
            ->setQuery($searchQuery)
            ->setSize(self::MAX_ITEMS_PER_PAGE)
            ->setSort([
                'name' => 'asc',
            ])
        ;

        return $this->index->search($query)
            ->getResults()
        ;
    }

    private function buildSearchQuery(?string $query): AbstractQuery
    {
        if (!$query) {
            return new Query\MatchAll();
        }

        return (new Query\MultiMatch())
            ->setQuery($query)
            ->setFields(['name^3', 'authorName'])
        ;
    }
}
