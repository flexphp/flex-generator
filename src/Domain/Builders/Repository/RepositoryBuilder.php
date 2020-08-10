<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Repository;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaInterface;

final class RepositoryBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, array $actions)
    {
        $login = 'email';
        $item = $this->getInflector()->item($schema->name());
        $entity = $this->getInflector()->entity($schema->name());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $pkName = $this->getInflector()->pascalProperty($schema->pkName());

        $requests = [];
        $actions = \array_reduce($actions, function (array $result, string $action) use (&$requests) {
            $result[] = $this->getInflector()->camelAction($action);
            $requests[] = $this->getInflector()->pascalAction($action);

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'item', 'actions', 'requests', 'login', 'fkFns', 'pkName'));
    }

    protected function getFileTemplate(): string
    {
        return 'Repository.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Repository', parent::getPathTemplate());
    }
}
