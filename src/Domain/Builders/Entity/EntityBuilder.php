<?php

namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class EntityBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Entity.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }
}
