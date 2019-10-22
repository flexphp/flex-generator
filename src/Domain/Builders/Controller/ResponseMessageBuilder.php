<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class ResponseMessageBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Response.php.twig';
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
