<?php

namespace App\Controller;

use App\Service\HashManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hash", name="hash_")
 */
class HashController extends AbstractController
{
    /**
     * @Route("/{text}", name="create", methods={"POST"})
     */
    public function create(String $text, Request $request, RateLimiterFactory $createHashLimiter): Response
    {
        $limiter = $createHashLimiter->create($request->getClientIp());

        if(!$limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $hashManager = new HashManager();
        $hashManager->generate($text);

        return new JsonResponse([
            "hash" => $hashManager->getHash(),
            "key" => $hashManager->getKey(),
            "amountTries" => $hashManager->getAmountTries()
        ]);
    }
}