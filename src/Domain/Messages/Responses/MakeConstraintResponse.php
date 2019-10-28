<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class MakeConstraintResponse implements ResponseInterface
{
    public $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function response()
    {
        return $this->file;
    }
}
