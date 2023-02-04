<?php

namespace App\Command;

use App\Entity\Hash;
use App\Repository\HashRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GenerateHashInBatchCommand extends Command
{
    private $client;
    private $router;
    protected static $defaultName = 'avato:test';
    private $hashRepository;

    public function __construct(
        HttpClientInterface $client,
        UrlGeneratorInterface $router,
        HashRepository $hashRepository)
    {
        $this->client = $client;
        $this->router = $router;
        $this->hashRepository = $hashRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('command to perform batch hash generation')
            ->addArgument('input_string', InputArgument::REQUIRED, 'input string')
            ->addOption('requests', null, InputOption::VALUE_REQUIRED,
                'number of requests', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputString = $input->getArgument('input_string');
        $requests = $input->getOption('requests');
        $batch = new \DateTime();
        $blockNumber = 1;

        while($blockNumber <= $requests) {
            try {
                $response = $this->client->request('POST', $this->router->generate('hash_create', [
                    'inputString' => $inputString
                ], UrlGeneratorInterface::ABSOLUTE_URL));
                $content = $response->toArray();

            } catch(HttpExceptionInterface $exception) {
                $headers = $exception->getResponse()->getHeaders(false);
                $sleepingTime = $headers['x-ratelimit-retry-after'][0];
                sleep($sleepingTime);

            } finally {
                $this->saveInDatabase(
                    $batch,
                    $content['hash'],
                    $content['amountTries'],
                    $blockNumber,
                    $inputString,
                    $content['keyFound']
                );

                $inputString = $content['hash'];
                $blockNumber++;
            }
        }

        return Command::SUCCESS;
    }

    private function saveInDatabase(
        \DateTime $batch,
        String $hashValue,
        int $amountTries,
        int $blockNumber,
        String $inputString,
        String $keyFound
    )
    {
        $hash = new Hash();
        $hash->setBatch($batch);
        $hash->setHash($hashValue);
        $hash->setAmountTries($amountTries);
        $hash->setBlockNumber($blockNumber);
        $hash->setInputString($inputString);
        $hash->setKeyFound($keyFound);

        $this->hashRepository->add($hash, True);
    }
}