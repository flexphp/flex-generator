<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

final class ConstraintBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $rules)
    {
        $entity = $this->getSingularize($this->getPascalCase($entity));

        parent::__construct(\compact('entity', 'rules'));
    }

    protected function getFileTemplate(): string
    {
        return 'Constraint.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Constraint', parent::getPathTemplate());
    }
}
