<?php

namespace App\Model;

class Search
{
    public function __construct(
        private ?string $query = null
    ) {
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }
}
