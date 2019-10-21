<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

class UseCaseBuilder extends ControllerBuilder
{
    public function getFileTemplate(): string
    {
        return 'UseCase.php.twig';
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }
}
