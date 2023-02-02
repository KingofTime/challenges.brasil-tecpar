<?php

namespace App\Controller;

use App\Service\HashManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hash", name="hash_")
 */
class HashController
{
    /**
     * @Route("/{text}", name="create", methods={"POST"})
     */
    public function create(String $text): Response
    {
        $hashManager = new HashManager();
        $hashManager->generate($text);

        return new JsonResponse([
            "hash" => $hashManager->getHash(),
            "key" => $hashManager->getKey(),
            "amountTries" => $hashManager->getAmountTries()
        ]);
    }
}