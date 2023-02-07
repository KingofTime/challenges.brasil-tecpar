<?php

namespace App\Tests\Service;

use App\Service\Parameters;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class ParametersTest extends TestCase
{
    public function testPerPageParamater(): void
    {
        $per_page = 10;

        $_GET['per_page'] = $per_page;
        $requests = Request::createFromGlobals();

        $parameters = new Parameters($requests);

        self::assertEquals($per_page, $parameters->getPerPage());

    }

    public function testPageParameter(): void
    {
        $page = 1;

        $_GET['page'] = $page;
        $requests = Request::createFromGlobals();

        $parameters = new Parameters($requests);

        self::assertEquals($page, $parameters->getPage());
    }

    public function testFiltersParameter(): void
    {
        $amountTries = 1000;

        $_GET['amount_tries'] = $amountTries;
        $requests = Request::createFromGlobals();

        $parameters = new Parameters($requests);

        self::assertEquals(["amount_tries" => $amountTries], $parameters->getFilters());
    }
}