<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

class RequestMessageBuilder extends ControllerBuilder
{
    public function getFileTemplate(): string
    {
        return 'Request.php.twig';
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }
}
