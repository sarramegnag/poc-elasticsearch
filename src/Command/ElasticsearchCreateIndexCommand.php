<?php

namespace App\Command;

use App\Entity\Book;
use App\Repository\BookRepository;
use Elastica\Document;
use JoliCode\Elastically\IndexBuilder;
use JoliCode\Elastically\Indexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:elasticsearch:create-index',
    description: 'Build new index from scratch and populate.',
)]
class ElasticsearchCreateIndexCommand extends Command
{
    public function __construct(
        private readonly IndexBuilder $indexBuilder,
        private readonly Indexer $indexer,
        private readonly BookRepository $bookRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newIndex = $this->indexBuilder->createIndex('books');

        $progressBar = new ProgressBar($output, $this->bookRepository->count([]));

        /** @var \Generator<Book> $books */
        $allBooks = $this->bookRepository->createQueryBuilder('books')->getQuery()->toIterable();

        foreach ($allBooks as $book) {
            $this->indexer->scheduleIndex($newIndex, new Document($book->getId(), $book->toModel()));

            $progressBar->advance();
        }

        $this->indexer->flush();

        $this->indexBuilder->markAsLive($newIndex, 'books');
        $this->indexBuilder->speedUpRefresh($newIndex);
        $this->indexBuilder->purgeOldIndices('books');

        $progressBar->finish();

        return Command::SUCCESS;
    }
}
