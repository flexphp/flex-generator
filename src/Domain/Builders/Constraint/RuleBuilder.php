<?php

namespace FlexPHP\Generator\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class RuleBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $property = array_key_first($data);
        $rules = $data[$property];

        $_data = \compact('property', 'rules');

        parent::__construct($_data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Rule.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Constraint', parent::getPathTemplate());
    }

    public function buildX(): string
    {
        return rtrim(parent::build());
    }
}
