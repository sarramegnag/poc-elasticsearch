<?php

namespace App\Controller;

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private const MAX_ITEMS_PER_PAGE = 10;

    #[Route('/', name: 'app_book')]
    public function index(
        #[Autowire(service: 'fos_elastica.finder.book')]
        PaginatedFinderInterface $bookFinder,
        Request $request
    ): Response {
        $page = $request->query->get('page', 1);

        $books = $bookFinder->findPaginated([])
            ->setMaxPerPage(self::MAX_ITEMS_PER_PAGE)
            ->setCurrentPage($page);

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }
}
