<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class UseCaseBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'UseCase.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }
}
