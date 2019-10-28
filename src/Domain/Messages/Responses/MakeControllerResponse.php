<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class MakeControllerResponse implements ResponseInterface
{
    public $output;

    public function __construct(string $output)
    {
        $this->output = $output;
    }

    public function response()
    {
        return $this->output;
    }
}
