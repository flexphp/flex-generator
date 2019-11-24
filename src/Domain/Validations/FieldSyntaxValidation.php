<?php

namespace FlexPHP\Generator\Domain\Validations;

use Exception;
use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validators\PropertyConstraintsValidator;
use FlexPHP\Generator\Domain\Validators\PropertyDataTypeValidator;
use FlexPHP\Generator\Domain\Validators\PropertyNameValidator;
use FlexPHP\Generator\Domain\Validators\PropertyTypeValidator;
use Symfony\Component\Validator\ConstraintViolationList;

class FieldSyntaxValidation implements ValidationInterface
{
    protected $properties;

    private $allowedProperties = [
        Keyword::NAME,
        Keyword::DATA_TYPE,
        Keyword::TYPE,
        Keyword::CONSTRAINTS,
    ];
    
    private $validators = [
        Keyword::NAME => PropertyNameValidator::class,
        Keyword::DATA_TYPE => PropertyDataTypeValidator::class,
        Keyword::TYPE => PropertyTypeValidator::class,
        Keyword::CONSTRAINTS => PropertyConstraintsValidator::class,
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
                $violations = $this->validateProperty($property, $value);

                if (0 !== count($violations)) {
                    throw new FieldSyntaxValidationException(sprintf("%1\$s:\n%2\$s", $property, $violations));
                }
            }
        }
    }

    private function validateProperty(string $property, $value): ConstraintViolationList
    {
        try {
            $validator = new $this->validators[$property];
            $violations = $validator->validate($value);
        } catch (Exception $e) {
            throw new FieldSyntaxValidationException(sprintf("%1\$s:\n%2\$s", $property, $e->getMessage()));
        }

        return $violations;
    }
}
