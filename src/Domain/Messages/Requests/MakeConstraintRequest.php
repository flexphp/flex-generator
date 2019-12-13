<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class MakeConstraintRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var array
     */
    public $properties;

    /**
     * @param string $entity
     * @param array $properties
     */
    public function __construct(string $entity, array $properties)
    {
        $this->entity = $entity;
        $this->properties = $properties;
    }
}
