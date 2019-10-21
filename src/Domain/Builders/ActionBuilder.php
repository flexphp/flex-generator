<?php

namespace FlexPHP\Generator\Domain\Builders;

class ActionBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Action.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
