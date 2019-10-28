<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyDataTypeValidator
{
    const ALLOWED_DATA_TYPES = [
        'smallint',
        'integer',
        'bigint',
        'decimal',
        'float',
        'string',
        'text',
        'guid',
        'binary',
        'blob',
        'boolean',
        'date',
        'datetime',
        'datetimetz',
        'time',
        'array',
        'json_array',
        'object',
    ];

    public function validate($dataType)
    {
        $validator = Validation::createValidator();

        return $validator->validate($dataType, [
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
            new Choice(self::ALLOWED_DATA_TYPES),
        ]);
    }
}
