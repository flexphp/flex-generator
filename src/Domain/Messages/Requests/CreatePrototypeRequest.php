<?php

namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Messages\RequestInterface;

class CreatePrototypeRequest implements RequestInterface
{
    /**
     * @var array
     */
    public $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }
}
