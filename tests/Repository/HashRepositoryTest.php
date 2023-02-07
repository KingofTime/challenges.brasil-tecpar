<?php

namespace App\Tests\Repository;

use App\Entity\Hash;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HashRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $hash = new Hash();
        $hash->setBatch(new \DateTime());
        $hash->setHash('hash_teste');
        $hash->setAmountTries(100);
        $hash->setBlockNumber(1);
        $hash->setInputString('input_string_teste');
        $hash->setKeyFound('x300');


        $hash2 = new Hash();
        $hash2->setBatch(new \DateTime());
        $hash2->setHash('hash_teste_2');
        $hash2->setAmountTries(500);
        $hash2->setBlockNumber(1);
        $hash2->setInputString('input_string_teste_2');
        $hash2->setKeyFound('x56f');

        $this->entityManager
            ->getRepository(Hash::class)
            ->add($hash, True);

        $this->entityManager
            ->getRepository(Hash::class)
            ->add($hash2, True);
    }

    public function testPaginate()
    {
        $collection = $this->entityManager
            ->getRepository(Hash::class)
            ->paginate(
                ['amount_tries' => 100], 10, 1
            );

        self::assertCount(1, $collection);
        self::assertEquals('hash_teste', $collection[0]->getHash());
    }
}