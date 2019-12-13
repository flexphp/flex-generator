<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class SheetProcessResponse implements ResponseInterface
{
    /**
     * @var array
     */
    public $response;

    /**
     * @var string|null
     */
    public $controller;

    /**
     * @var string|null
     */
    public $constraint;

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->controller = $response['controller'] ?? null;
        $this->constraint = $response['constraint'] ?? null;
    }
}
