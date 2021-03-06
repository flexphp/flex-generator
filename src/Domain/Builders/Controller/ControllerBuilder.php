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
use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\SchemaInterface;

final class ControllerBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, array $actions)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $route = $this->getInflector()->route($schema->name());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $header = self::getHeaderFile();
        $hasFilter = $schema->hasAction(Action::FILTER);
        unset($actions['login']);

        foreach ($actions as $action => $builder) {
            $actions[$this->getInflector()->pascalAction($action)] = $builder;
            unset($actions[$action]);
        }

        parent::__construct(\compact('entity', 'actions', 'route', 'fkFns', 'header', 'hasFilter'));
    }

    protected function getFileTemplate(): string
    {
        return 'Controller.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/src/Controller', parent::getPathTemplate());
    }
}
