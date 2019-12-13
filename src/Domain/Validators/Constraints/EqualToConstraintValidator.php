<?php

namespace FlexPHP\Generator\Domain\Validators\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class EqualToConstraintValidator
{
    /**
     * @param mixed $string
     * @return ConstraintViolationListInterface
     */
    public function validate($string): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($string, [
            new NotBlank(),
        ]);
    }
}
