<?php

namespace App\Model;

readonly class Book
{
    public function __construct(
        private string $name,
        private string $authorName
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}
