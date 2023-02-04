<?php

namespace App\Service;

use Symfony\Component\String\ByteString;
use function Symfony\Component\String\u;

class HashManager
{
    private String $hash;
    private String $keyFound;
    private int $amountTries=0;

    public function generate(String $inputString)
    {
        do {
            $key = ByteString::fromRandom(8)->toString();
            $hash = md5($inputString.$key);

            $this->amountTries++;
        } while(!u($hash)->startsWith('0000'));

        $this->keyFound = $key;
        $this->hash = $hash;
    }

    public function getHash(): String
    {
        return $this->hash;
    }

    public function getKeyFound(): String
    {
        return $this->keyFound;
    }

    public function getAmountTries(): int
    {
        return $this->amountTries;
    }
}