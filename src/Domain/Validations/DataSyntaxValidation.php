<?php

namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Exceptions\DataSyntaxValidationException;

class DataSyntaxValidation implements ValidationInterface
{
    protected $data;
    protected $isValid;
    protected $allowedHeaders = [
        'Name',
        'DataType',
    ];

    protected $requiredHeaders = [
        'Name',
        'DataType',
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(): void
    {
        $notAllowedHeaders = [];
        $requiredHeadersNotPresent = [];

        foreach ($this->data as $header) {
            if (!\in_array($header, $this->allowedHeaders)) {
                $notAllowedHeaders[] = $header;
            }
        }

        if (!empty($notAllowedHeaders)) {
            throw new DataSyntaxValidationException('Unknow headers: ' . implode(', ', $notAllowedHeaders));
        }

        foreach ($this->requiredHeaders as $header) {
            if (!\in_array($header, $this->data)) {
                $requiredHeadersNotPresent[] = $header;
            }
        }

        if (!empty($requiredHeadersNotPresent)) {
            throw new DataSyntaxValidationException('Required headers not present: ' . implode(', ', $requiredHeadersNotPresent));
        }
    }
}
