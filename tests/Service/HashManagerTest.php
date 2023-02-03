<?php

namespace App\Tests\Service;

use App\Service\HashManager;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class HashManagerTest extends TestCase
{
    public function testHashGeneration(): void
    {
        $text = "abacate maduro";
        $hashManager = new HashManager();
        $hashManager->generate($text);

        $expectedHash = md5($text.$hashManager->getKey());

        assertTrue($expectedHash == $hashManager->getHash());
    }

    public function testNewHashInThePattern(): void
    {
        $text = "abacate maduro";
        $hashManager = new HashManager();
        $hashManager->generate($text);

        $hash = $hashManager->getHash();

        assertTrue(substr($hash, 0, 4) == '0000');
    }
}