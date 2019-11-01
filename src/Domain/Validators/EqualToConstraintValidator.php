<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class EqualToConstraintValidator
{
    public function validate($string)
    {
        $validator = Validation::createValidator();

        return $validator->validate($string, [
            new NotBlank(),
        ]);
    }
}
