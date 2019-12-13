<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class MakeControllerRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var array
     */
    public $actions;

    public function __construct(string $entity, array $actions)
    {
        $this->entity = $entity;
        $this->actions = $actions;
    }
}
