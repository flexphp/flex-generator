<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class CreateEntityResponse implements ResponseInterface
{
    /**
     * @var string
     */
    public $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }
}
