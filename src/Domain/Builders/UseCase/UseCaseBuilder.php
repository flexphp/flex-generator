<?php declare(strict_types=1);

namespace FlexPHP\Generator\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class UseCaseBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'UseCase.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }
}
