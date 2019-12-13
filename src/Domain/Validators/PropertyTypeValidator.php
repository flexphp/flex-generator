<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyTypeValidator
{
    const ALLOWED_TYPES = [
        'text',
        'textarea',
        'email',
        'number',
        'integer',
        'digits',
        'alphanum',
        'url',
        'range',
        'pattern',
    ];

    public function validate(string $type): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($type, [
            new NotBlank(),
            new Choice(self::ALLOWED_TYPES),
        ]);
    }
}
