<?php

namespace App\Service;

use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\String\ByteString;
use function Symfony\Component\String\u;

class HashManager
{
    private String $hash;
    private String $key;
    private int $amountTries=0;

    public function generate(String $text)
    {
        do {
            $key = ByteString::fromRandom(8)->toString();
            $hash = md5($text.$key);

            $this->amountTries++;
        } while(!u($hash)->startsWith('0000'));

        $this->key = $key;
        $this->hash = $hash;
    }

    public function getHash(): String
    {
        return $this->hash;
    }

    public function getKey(): String
    {
        return $this->key;
    }

    public function getAmountTries(): int
    {
        return $this->amountTries;
    }
}