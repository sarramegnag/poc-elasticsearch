<?php

namespace App\Controller;

use App\Form\CreateBookType;
use App\Form\SearchType;
use App\Model\Book;
use App\Model\Search;
use App\Repository\BookRepository;
use Elastica\Query;
use JoliCode\Elastically\Client;
use JoliCode\Elastically\Messenger\IndexationRequest;
use JoliCode\Elastically\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly MessageBusInterface $bus
    ) {
    }

    #[Route('/', name: 'app_book')]
    public function index(Client $client, Request $request): Response
    {
        $form = $this->createForm(SearchType::class, $search = new Search());

        $form->handleRequest($request);

        if ($form->isSubmitted() && !$search->getQuery()) {
            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        if ($search->getQuery()) {
            $searchQuery = (new Query\MultiMatch())
                ->setQuery($search->getQuery())
                ->setFields(['name^3', 'authorName'])
            ;
        } else {
            $searchQuery = new Query\MatchAll();
        }

        $query = (new Query())
            ->setQuery($searchQuery)
            ->setSize(20)
        ;

        /** @var Result[] $books */
        $books = $client->getIndex('books')->search($query)->getResults();

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: 'app_book_create')]
    public function create(Request $request): Response
    {
        return $this->handleForm(new \App\Entity\Book(), $request);
    }

    #[Route('/update/{id}', name: 'app_book_update')]
    public function update(\App\Entity\Book $book, Request $request): Response
    {
        return $this->handleForm($book, $request);
    }

    private function handleForm(\App\Entity\Book $book, Request $request): Response
    {
        $isUpdate = $book->getId() !== null;

        $form = $this->createForm(CreateBookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookRepository->save($book, true);
            $this->bus->dispatch(new IndexationRequest(Book::class, $book->getId()));

            $this->addFlash('success', sprintf('Book has been %s.', $isUpdate ? 'updated' : 'created'));

            return $this->redirectToRoute('app_book');
        }

        return $this->render(sprintf(
            'book/%s.html.twig',
            $isUpdate ? 'update' : 'create'
        ), [
            'book' => $book,
            'form' => $form,
        ]);
    }
}
