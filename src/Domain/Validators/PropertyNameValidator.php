<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyNameValidator
{
    private $minLength = 1;
    private $maxLength = 64;

    public function validate($name)
    {
        $validator = Validation::createValidator();

        return $validator->validate($name, [
            new NotBlank(),
            new Length([
                'min' => $this->minLength,
                'max' => $this->maxLength,
            ]),
            new Regex([
                'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            ]),
        ]);
    }
}
