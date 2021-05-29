<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class UseCaseBuilder extends AbstractBuilder
{
    private string $action;

    public function __construct(SchemaInterface $schema, string $action)
    {
        $this->action = $this->getInflector()->camelAction($action);

        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $items = $this->getInflector()->items($schema->name());
        $action = $this->getInflector()->pascalAction($action);
        $fkFns = $this->getFkFunctions($this->getFkCheck($schema->fkRelations()));
        $properties = \array_reduce(
            $schema->attributes(),
            function ($result, SchemaAttributeInterface $property) {
                $result[$this->getInflector()->camelProperty($property->name())] = $property->properties();

                return $result;
            },
            []
        );
        $header = self::getHeaderFile();

        parent::__construct(\compact('header', 'entity', 'item', 'items', 'action', 'properties', 'fkFns'));
    }

    protected function getFileTemplate(): string
    {
        if (\in_array($this->action, ['index', 'create', 'read', 'update', 'delete', 'login'])) {
            return $this->action . '.php.twig';
        }

        return 'default.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }

    private function getFkCheck(array $relations)
    {
        return \array_reduce($relations, function (array $result, array $relation) {
            if (!empty($relation['check'])) {
                $result[] = $relation;
            }

            return $result;
        }, []);
    }
}
