<?php

namespace FlexPHP\Generator\Domain\Validators;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationList;
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
        $violations = new ConstraintViolationList();

        if (empty($constraints)) {
            return $violations;
        }

        if (is_string($constraints)) {
            $constraints = $this->getConstraintsFromString($constraints);
        }

        if (!is_array($constraints)) {
            throw new InvalidArgumentException('Constraints: Format not supported');
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
                $errors = $this->validateRule($rule, $options);
            }

            if (count($errors) !== 0) {
                foreach ($errors as $error) {
                    $violations->add($error);
                }
            }
        }

        return $violations;
    }

    private function getConstraintsFromString(string $constraints)
    {
        // Json syntax
        $_constraints = \json_decode($constraints, true);

        if (\is_null($_constraints) && \strpos($constraints, '[') === 0) {
            // Array syntax
            eval(sprintf('$_constraints = %1$s;', $constraints));
        }

        if (!\is_array($_constraints)) {
            // String syntax
            $_constraints = \explode('|', $constraints);

            if (\count($_constraints) > 0) {
                /** @var string $_constraint */
                foreach ($_constraints as $index => $_constraint) {
                    $_rule = \explode(':', $_constraint);

                    if (\count($_rule) == 2) {
                        list($_name, $_options) = $_rule;
                        $_constraints[$_name] = $_options;
                    } else {
                        $_constraints[$_rule[0]] = true;
                    }

                    unset($_constraints[$index]);
                }
            }
        }

        return $_constraints;
    }

    private function validateRule(string $rule, $options): ConstraintViolationList
    {
        $errors = new ConstraintViolationList();

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

        return $errors;
    }
}
