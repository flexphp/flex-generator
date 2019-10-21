<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

class ResponseMessageBuilder extends ControllerBuilder
{
    public function getFileTemplate(): string
    {
        return 'Response.php.twig';
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }
}
