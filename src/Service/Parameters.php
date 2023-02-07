<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class Parameters
{
    private array $pagination = [];
    private $filters = [];

    public function __construct(Request $request)
    {
        $this->parse($request->query->all());
    }

    public function parse(array $parameters): void
    {
        $this->pagination["page"] = array_key_exists('page', $parameters) ? $parameters['page']: 1;
        $this->pagination["per_page"] = array_key_exists('per_page', $parameters) ? $parameters['per_page']: 10;

        unset($parameters['page']);
        unset($parameters['per_page']);

        $this->filters = $parameters;
    }

    public function getPage(): int
    {
        return $this->pagination['page'];
    }

    public function getPerPage(): int
    {
        return $this->pagination['per_page'];
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}