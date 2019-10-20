<?php

namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validators\PropertyNameValidator;

class FieldSyntaxValidation implements ValidationInterface
{
    protected $data;

    private $validators = [
        Header::NAME => PropertyNameValidator::class,
        Header::DATA_TYPE => PropertyNameValidator::class,
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(): void
    {
        foreach ($this->data as $property => $value) {
            if (!in_array($property, array_keys($this->validators))) {
                throw new FieldSyntaxValidationException('Property unknow: ' . $property);
            }

            $validator = new $this->validators[$property];
            $violations = $validator->validate($value);

            if (0 !== count($violations)) {
                throw new FieldSyntaxValidationException($property . (string)$violations);
            }
        }
    }
}
