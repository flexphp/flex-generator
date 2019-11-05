<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class SheetProcessResponse implements ResponseInterface
{
    public $response;
    public $controller;
    public $constraint;

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->controller = $response['controller'] ?? null;
        $this->constraint = $response['constraint'] ?? null;
    }
}
