<?php

namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;
use FlexPHP\Generator\Domain\Constants\Header;

class HeaderSyntaxValidation implements ValidationInterface
{
    protected $headers;

    protected $allowedHeaders = [
        Header::NAME,
        Header::DATA_TYPE,
    ];

    protected $requiredHeaders = [
        Header::NAME,
        Header::DATA_TYPE,
    ];

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function validate(): void
    {
        $notAllowedHeaders = [];
        $requiredHeadersNotPresent = [];

        foreach ($this->headers as $header) {
            if (!\in_array($header, $this->allowedHeaders)) {
                $notAllowedHeaders[] = $header;
            }
        }

        if (!empty($notAllowedHeaders)) {
            throw new HeaderSyntaxValidationException('Unknow headers: ' . implode(', ', $notAllowedHeaders));
        }

        foreach ($this->requiredHeaders as $header) {
            if (!\in_array($header, $this->headers)) {
                $requiredHeadersNotPresent[] = $header;
            }
        }

        if (!empty($requiredHeadersNotPresent)) {
            throw new HeaderSyntaxValidationException('Required headers not present: ' . implode(', ', $requiredHeadersNotPresent));
        }
    }
}
