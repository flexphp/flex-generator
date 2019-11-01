<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class MaxConstraintValidator
{
    public function validate($max)
    {
        $validator = Validation::createValidator();

        return $validator->validate($max, [
            new NotBlank(),
            new Positive(),
        ]);
    }
}
