<?php

namespace App\ElasticRepository;

use Elastica\Aggregation;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Repository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookRepository extends Repository
{
    private const MAX_ITEMS_PER_PAGE = 20;

    public function __construct(
        #[Autowire(service: 'fos_elastica.finder.book')]
        PaginatedFinderInterface $finder
    ) {
        parent::__construct($finder);
    }

    public function findAll(int $page, int $limit = self::MAX_ITEMS_PER_PAGE): array
    {
        $query = new Query();
        $booksAgg = (new Aggregation\Terms('books_agg'))
            ->setField('id')
        ;
        $query->addAggregation($booksAgg);

        $reviewsAgg = new Aggregation\Nested('reviews_agg', 'reviews');
        $booksAgg->addAggregation($reviewsAgg);

        $ratingAvg = (new Aggregation\Avg('rating_avg'))
            ->setField('reviews.rating')
        ;
        $reviewsAgg->addAggregation($ratingAvg);

        return $this->find($query)
            //->setMaxPerPage($limit)
            //->setCurrentPage($page)
        ;
    }
}
