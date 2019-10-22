<?php

namespace FlexPHP\Generator\Domain\Builders\Command;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class CommandBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Command.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Command', parent::getPathTemplate());
    }
}
