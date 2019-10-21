<?php

namespace FlexPHP\Generator\Domain\Builders;

class RequestMessageBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Request.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Message', parent::getPathTemplate());
    }
}
