<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

final class ControllerBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $actions)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $route = $this->getDashCase($this->getPluralize($entity));
        unset($actions['login']);

        foreach ($actions as $action => $builder) {
            $actions[$this->getPascalCase($action)] = $builder;
            unset($actions[$action]);
        }

        parent::__construct(\compact('entity', 'actions', 'route'));
    }

    protected function getFileTemplate(): string
    {
        return 'Controller.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
