<?php

namespace FlexPHP\Generator\Domain\Exceptions;

class FormatNotSupportedException extends DomainException
{
    protected $message = 'Format isn\'t supported';
}
