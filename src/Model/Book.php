<?php

namespace App\Model;

readonly class Book
{
    public function __construct(
        private int $id,
        private string $name,
        private int $authorId,
        private string $authorName
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}
