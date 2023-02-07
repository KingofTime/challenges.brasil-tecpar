<?php

namespace App\Tests\Service;

use App\Entity\Hash;
use App\Service\HashManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HashManagerTest extends KernelTestCase
{
    public function testHashGeneration(): void
    {
        $container = static::getContainer();

        $hashManager = $container->get(HashManager::class);
        $inputString = "abacate maduro";

        $hashManager->generate($inputString);
        $expectedHash = md5($inputString.$hashManager->getKeyFound());

        self::assertTrue($expectedHash == $hashManager->getHash());
    }

    public function testNewHashInThePattern(): void
    {
        $container = static::getContainer();

        $hashManager = $container->get(HashManager::class);
        $inputString = "abacate maduro";

        $hashManager->generate($inputString);
        $hash = $hashManager->getHash();

        self::assertTrue(substr($hash, 0, 4) == '0000');
    }

    public function testSaveHashInDatabase(): void
    {
        $container = static::getContainer();

        $hashManager = $container->get(HashManager::class);
        $hashRepository = ($container->get(ManagerRegistry::class))
            ->getRepository(Hash::class);

        $batch = new \DateTime();
        $inputString = "abacate maduro";

        $hashManager->generate($inputString);
        $hashManager->setBatch($batch);
        $hashManager->setBlockNumber(1);
        $hashManager->setInputString($inputString);

        $hashManager->save();

        $hash = $hashRepository->findBy(['inputString' => $hashManager->getInputString()])[0];

        self::assertEquals($hash->getHash(), $hashManager->getHash());
        self::assertEquals($hash->getKeyFound(), $hashManager->getKeyFound());
    }
}