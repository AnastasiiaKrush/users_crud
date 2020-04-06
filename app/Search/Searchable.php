<?php

namespace App\Search;

trait Searchable
{
    /**
     * Return search index.
     *
     * @return string
     */
    public function getSearchIndex(): string
    {
        return $this->getTable();
    }

    /**
     * Return search type.
     *
     * @return string
     */
    public function getSearchType(): string
    {
        return $this->getTable();
    }

    /**
     * Transform the model to a searchable array.
     *
     * @return array
     */
    public function toSearchArray(): array
    {
        return $this->toArray();
    }
}
