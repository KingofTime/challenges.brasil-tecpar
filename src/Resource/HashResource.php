<?php

namespace App\Resource;

use App\Entity\Hash;

class HashResource
{
    private Hash $hash;

    public function __construct(Hash $hash)
    {
        $this->hash = $hash;
    }

    public function toArray(): array
    {
        return [
            "batch" => $this->hash->getBatch()->format('d/m/Y H:i'),
            "blockNumber" => $this->hash->getBlockNumber(),
            "inputString" => $this->hash->getInputString(),
            "keyFound" => $this->hash->getKeyFound()
        ];
    }
}