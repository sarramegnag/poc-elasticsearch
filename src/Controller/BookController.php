<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\Book;
use App\Model\Search;
use Elastica\Query;
use JoliCode\Elastically\Client;
use JoliCode\Elastically\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(
        Client $client,
        Request $request
    ): Response {
        $form = $this->createForm(SearchType::class, $search = new Search());

        $form->handleRequest($request);

        if ($form->isSubmitted() && !$search->getQuery()) {
            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        if ($search->getQuery()) {
            $searchQuery = (new Query\MultiMatch())
                ->setQuery($search->getQuery())
                ->setFields(['name', 'authorName'])
            ;
        } else {
            $searchQuery = new Query\MatchAll();
        }

        $query = (new Query())
            ->setQuery($searchQuery)
            ->setSize(20)
        ;

        return $this->render('book/index.html.twig', [
            'books' => array_map(
                fn (Result $result): Book => $result->getModel(),
                $client->getIndex('books')->search($query)->getResults()
            ),
            'form' => $form,
        ]);
    }
}
