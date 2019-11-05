<?php

namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

class ProcessFormatResponse implements ResponseInterface
{
    public $messages;
    public $hasError;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
        $this->hasError = !empty($messages['error']);
    }
}
