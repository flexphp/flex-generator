<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class SheetProcessResponse implements ResponseInterface
{
    public $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function response()
    {
        return $this->response;
    }
}
