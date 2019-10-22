<?php

namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validators\PropertyDataTypeValidator;
use FlexPHP\Generator\Domain\Validators\PropertyNameValidator;

class FieldSyntaxValidation implements ValidationInterface
{
    protected $properties;

    private $allowedProperties = [
        Header::NAME,
        Header::DATA_TYPE,
    ];
    
    private $validators = [
        Header::NAME => PropertyNameValidator::class,
        Header::DATA_TYPE => PropertyDataTypeValidator::class,
    ];

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function validate(): void
    {
        foreach ($this->properties as $property => $value) {
            if (!in_array($property, $this->allowedProperties)) {
                throw new FieldSyntaxValidationException('Property unknow: ' . $property);
            }

            if (in_array($property, array_keys($this->validators))) {
                $validator = new $this->validators[$property];
                $violations = $validator->validate($value);

                if (0 !== count($violations)) {
                    throw new FieldSyntaxValidationException($property . ': ' .  $violations);
                }
            }
        }
    }
}
