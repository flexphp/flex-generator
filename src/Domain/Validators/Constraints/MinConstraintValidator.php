<?php

namespace FlexPHP\Generator\Domain\Validators\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class MinConstraintValidator
{
    public function validate(?int $min): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($min, [
            new NotBlank(),
            new PositiveOrZero(),
        ]);
    }
}
