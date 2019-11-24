<?php

namespace FlexPHP\Generator\Domain\Validators\Constraints;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class RequiredConstraintValidator
{
    public function validate($bool)
    {
        $validator = Validation::createValidator();

        return $validator->validate($bool, [
            new NotNull(),
            new Choice([true, false, 'true', 'false']),
        ]);
    }
}
