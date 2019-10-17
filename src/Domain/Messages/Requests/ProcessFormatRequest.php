<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class ProcessFormatRequest implements RequestInterface
{
    public $path;
    public $extension;

    public function __construct(string $path, string $extension)
    {
        $this->path = $path;
        $this->extension = $extension;
    }
}
