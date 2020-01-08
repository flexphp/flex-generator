<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class ProcessFormatRequest implements RequestInterface
{
    /**
     * @var string|bool
     */
    public $path;

    /**
     * @var string|null
     */
    public $extension;

    /**
     * @param string|bool $path
     * @param string|null $extension
     */
    public function __construct($path, ?string $extension)
    {
        $this->path = $path;
        $this->extension = $extension;
    }
}
