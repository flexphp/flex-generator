<?php

namespace FlexPHP\Generator\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class ConstraintBuilder extends AbstractBuilder
{
    public function getFileTemplate(): string
    {
        return 'Constraint.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Constraint', parent::getPathTemplate());
    }
}
