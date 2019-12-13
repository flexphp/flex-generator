<?php

namespace FlexPHP\Generator\Domain\Validators;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyNameValidator
{
    /**
     * @var integer
     */
    private $minLength = 1;

    /**
     * @var integer
     */
    private $maxLength = 64;

    public function validate(string $name): ConstraintViolationListInterface
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
