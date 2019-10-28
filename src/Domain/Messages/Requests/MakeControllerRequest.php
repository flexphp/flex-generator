<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class MakeControllerRequest implements RequestInterface
{
    public $entity;
    public $actions;

    public function __construct(string $entity, string $actions)
    {
        $this->entity = $entity;
        $this->actions = $actions;
    }
}
