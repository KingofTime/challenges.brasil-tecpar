<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HashRepository::class)
 */
class Hash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $batch;

    /**
     * @ORM\Column(type="smallint")
     */
    private $blockNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inputString;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $keyFound;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountTries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBlockNumber(): ?int
    {
        return $this->blockNumber;
    }

    public function setBlockNumber(int $blockNumber): self
    {
        $this->blockNumber = $blockNumber;

        return $this;
    }

    public function getInputString(): ?string
    {
        return $this->inputString;
    }

    public function setInputString(string $inputString): self
    {
        $this->inputString = $inputString;

        return $this;
    }

    public function getKeyFound(): ?string
    {
        return $this->keyFound;
    }

    public function setKeyFound(string $keyFound): self
    {
        $this->keyFound = $keyFound;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAmountTries(): ?int
    {
        return $this->amountTries;
    }

    public function setAmountTries(int $amountTries): self
    {
        $this->amountTries = $amountTries;

        return $this;
    }
}
