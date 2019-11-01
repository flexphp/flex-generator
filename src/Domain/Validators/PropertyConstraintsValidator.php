<?php

namespace FlexPHP\Generator\Domain\Validators;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

/**
 * @Annotation
 */
class PropertyConstraintsValidator
{
    const ALLOWED_RULES = [
        'required',
        'minlength',
        'maxlength',
        'length',
        'mincheck',
        'maxcheck',
        'check',
        'min',
        'max',
        'equalto',
        'type',
    ];

    public function validate($constraints)
    {
        if (!is_array($constraints)) {
            throw new InvalidArgumentException('Constraints: Must be a array');
        }

        $validator = Validation::createValidator();

        foreach ($constraints as $rule => $options) {
            if (is_string($options) && $options == 'required') {
                $rule = $options;
                $options = true;
            }

            $errors = $validator->validate($rule, [
                new NotBlank(),
                new Choice(self::ALLOWED_RULES),
            ]);

            if (count($errors) === 0) {
                switch ($rule) {
                    case 'required':
                        $errors = (new RequiredConstraintValidator())->validate($options);
                        break;
                    case 'max':
                    case 'maxlength':
                    case 'maxcheck':
                        $errors = (new MaxConstraintValidator())->validate($options);
                        break;
                    case 'min':
                    case 'minlength':
                    case 'mincheck':
                        $errors = (new MinConstraintValidator())->validate($options);
                        break;
                    case 'equalto':
                        $errors = (new EqualToConstraintValidator())->validate($options);
                        break;
                    case 'type':
                        $errors = (new TypeConstraintValidator())->validate($options);
                        break;
                    case 'length':
                    case 'check':
                        $errors = (new RangeConstraintValidator())->validate($options);
                        break;
                }
            }
            // dump($constraints, $rule,  $options, (string)$errors);
        }

        return $errors;
    }
}
