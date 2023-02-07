<?php

namespace App\Service;

use App\Entity\Hash;
use App\Repository\HashRepository;
use Symfony\Component\String\ByteString;
use function Symfony\Component\String\u;

class HashManager
{
    private \DateTime $batch;
    private String $hash;
    private String $keyFound;
    private int $amountTries=0;
    private String $inputString;
    private int $blockNumber;

    private $hashRepository;

    public function __construct(HashRepository $hashRepository)
    {
        $this->hashRepository = $hashRepository;
    }

    public function generate(String $inputString)
    {
        do {
            $key = ByteString::fromRandom(8)->toString();
            $hash = md5($inputString.$key);

            $this->amountTries++;
        } while(!u($hash)->startsWith('0000'));

        $this->keyFound = $key;
        $this->hash = $hash;
        $this->inputString = $inputString;
    }

    public function save()
    {
        $hash = new Hash();
        $hash->setBatch($this->batch);
        $hash->setHash($this->hash);
        $hash->setAmountTries($this->amountTries);
        $hash->setBlockNumber($this->blockNumber);
        $hash->setInputString($this->inputString);
        $hash->setKeyFound($this->keyFound);

        $this->hashRepository->add($hash, True);
    }

    public function getBatch(): \DateTime
    {
        return $this->batch;
    }
    public function setBatch(\DateTime $batch)
    {
        $this->batch = $batch;
    }

    public function getHash(): String
    {
        return $this->hash;
    }
    public function setHash(String $hash)
    {
        $this->hash = $hash;
    }

    public function getKeyFound(): String
    {
        return $this->keyFound;
    }
    public function setKeyFound(String $keyFound)
    {
        $this->keyFound = $keyFound;
    }

    public function getAmountTries(): int
    {
        return $this->amountTries;
    }
    public function setAmountTries(int $amountTries)
    {
        $this->amountTries = $amountTries;
    }

    public function getBlockNumber(): int
    {
        return $this->blockNumber;
    }
    public function setBlockNumber(int $blockNumber)
    {
        $this->blockNumber = $blockNumber;
    }

    public function getInputString(): String
    {
        return $this->inputString;
    }
    public function setInputString(String $inputString)
    {
        $this->inputString = $inputString;
    }
}