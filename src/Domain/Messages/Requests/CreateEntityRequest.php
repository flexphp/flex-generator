<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class CreateEntityRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $properties;

    public function __construct(string $name, array $properties)
    {
        $this->name = $name;
        $this->properties = $properties;
    }
}
