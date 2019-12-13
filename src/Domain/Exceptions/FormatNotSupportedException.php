<?php

namespace FlexPHP\Generator\Domain\Exceptions;

class FormatNotSupportedException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Format isn\'t supported';
}
