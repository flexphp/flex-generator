<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class SheetProcessRequest implements RequestInterface
{
    public $name;
    public $path;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }
}
