<?php

namespace App\Resource;

class HashCollection
{
    private array $collection;

    public function __construct(array $collection)
    {
        $this->collection = array_map(function ($hash) {
            return (new HashResource($hash))->toArray();
        }, $collection);
    }

    public function toArray(): array
    {
        return $this->collection;
    }

}