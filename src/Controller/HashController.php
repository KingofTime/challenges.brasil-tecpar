<?php

namespace App\Controller;

use App\Repository\HashRepository;
use App\Resource\HashCollection;
use App\Service\HashManager;
use App\Service\Parameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hash", name="hash_")
 */
class HashController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request, HashRepository $hashRepository)
    {
        $parameters = new Parameters($request);

        $collection = $hashRepository->paginate(
            $parameters->getFilters(),
            $parameters->getPerPage(),
            $parameters->getPage()
        );

        $hashCollection = new HashCollection($collection);

        return new JsonResponse($hashCollection->toArray(), 200);
    }

    /**
     * @Route("/{inputString}", name="create", methods={"POST"})
     */
    public function create(
        String $inputString,
        Request $request,
        RateLimiterFactory $createHashLimiter,
        HashManager $hashManager
    ): Response
    {
        $limiter = $createHashLimiter->create($request->getClientIp());
        $limit = $limiter->consume();
        $headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->diff(new \DateTime())->s,
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];

        if(!$limit->isAccepted()) {
            return new JsonResponse("Too Many Attempts", Response::HTTP_TOO_MANY_REQUESTS, $headers);
        }

        $hashManager->generate($inputString);

        return new JsonResponse([
            "hash" => $hashManager->getHash(),
            "keyFound" => $hashManager->getKeyFound(),
            "amountTries" => $hashManager->getAmountTries()
        ], Response::HTTP_OK, $headers);
    }
}