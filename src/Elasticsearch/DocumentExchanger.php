<?php

namespace App\Elasticsearch;

use App\Model\Book;
use App\Repository\BookRepository;
use Elastica\Document;
use JoliCode\Elastically\Messenger\DocumentExchangerInterface;

readonly class DocumentExchanger implements DocumentExchangerInterface
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    public function fetchDocument(string $className, string $id): ?Document
    {
        if ($className !== Book::class) {
            return null;
        }

        $post = $this->bookRepository->find($id);

        if (!$post) {
            return null;
        }

        return new Document($id, $post->toModel());
    }
}
