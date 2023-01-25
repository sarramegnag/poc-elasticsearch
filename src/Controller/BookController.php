<?php

namespace App\Controller;

use App\ElasticRepository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(BookRepository $bookRepository, Request $request): Response
    {
        $page = $request->query->get('page', 1);

        dump($books = $bookRepository->findAll($page));

        $booksRatings = $books->getAdapter()->getAggregations()['books_agg']['buckets'];

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'bookRatings' => array_combine(
                array_map(fn (array $aggregation) => $aggregation['key'], $booksRatings),
                array_map(fn (array $aggregation) => $aggregation['reviews_agg']['rating_avg']['value'], $booksRatings),
            )
        ]);
    }
}
