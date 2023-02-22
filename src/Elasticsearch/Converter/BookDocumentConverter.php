<?php

namespace App\Elasticsearch\Converter;

use App\Entity\Book;
use App\Repository\BookRepository;
use Elastica\Document;

readonly class BookDocumentConverter implements DocumentConverterInterface
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    public function supports(string $className): bool
    {
        return $className === Book::class;
    }

    public function fetchDocument(string $id): ?Document
    {
        $post = $this->bookRepository->find($id);

        if (!$post) {
            return null;
        }

        return new Document($id, $post->toModel());
    }
}
