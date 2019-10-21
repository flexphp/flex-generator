<?php

namespace FlexPHP\Generator\Domain\Builders;

class ControllerBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Controller.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
