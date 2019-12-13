<?php

namespace FlexPHP\Generator\Domain\Validators\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class MaxConstraintValidator
{
    /**
     * @param mixed $max
     * @return ConstraintViolationListInterface
     */
    public function validate($max): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($max, [
            new NotBlank(),
            new Positive(),
        ]);
    }
}
