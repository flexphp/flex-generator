<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class MakeConstraintRequest implements RequestInterface
{
    public $entity;
    public $properties;

    public function __construct(string $entity, array $properties)
    {
        $this->entity = $entity;
        $this->properties = $properties;
    }
}
