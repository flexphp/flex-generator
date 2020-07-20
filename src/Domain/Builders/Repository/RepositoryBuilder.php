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

final class RepositoryBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $actions, array $properties)
    {
        $login = 'email';
        $item = $this->getCamelCase($this->getSingularize($entity));
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $fkRels = $this->getFkRelations($properties);

        $requests = [];
        $actions = \array_reduce($actions, function (array $result, string $action) use (&$requests) {
            $result[] = $this->getCamelCase($action);
            $requests[] = $this->getPascalCase($action);

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'item', 'actions', 'requests', 'login', 'fkRels'));
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
