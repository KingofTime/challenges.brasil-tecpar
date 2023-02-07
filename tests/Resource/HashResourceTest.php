<?php

namespace App\Tests\Resource;

use App\Entity\Hash;
use App\Resource\HashResource;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class HashResourceTest extends TestCase
{
    public function testHashToArray()
    {
        $batch = new \DateTime();

        $hash = new Hash();
        $hash->setHash('hash_teste');
        $hash->setBatch($batch);
        $hash->setInputString('input_string_teste');
        $hash->setBlockNumber(1);
        $hash->setAmountTries(1);
        $hash->setKeyFound('x300');

        $hashResource = new HashResource($hash);

        $expected = [
            "batch" => $batch->format('d/m/Y H:i'),
            "blockNumber" => 1,
            "inputString" => 'input_string_teste',
            "keyFound" => 'x300'
        ];

        assertEquals($expected, $hashResource->toArray());
    }
}