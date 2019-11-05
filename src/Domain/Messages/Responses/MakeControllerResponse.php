<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class MakeControllerResponse implements ResponseInterface
{
    public $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }
}
