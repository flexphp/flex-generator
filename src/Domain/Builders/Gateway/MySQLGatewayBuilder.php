<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Gateway;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class MySQLGatewayBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, array $actions)
    {
        $entity = $this->getPascalCase($this->getSingularize($schema->name()));
        $name = $this->getSnakeCase($this->getPluralize($entity));
        $item = $this->getCamelCase($this->getSingularize($schema->name()));
        $actions = \array_reduce($actions, function (array $result, string $action) {
            $result[] = $this->getCamelCase($action);

            return $result;
        }, []);

        $dbTypes = [];
        $pkName = $this->getCamelCase($schema->pkName());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $properties = \array_reduce(
            $schema->attributes(),
            function (array $result, SchemaAttributeInterface $property) use (&$dbTypes) {
                $camelName = $this->getCamelCase($property->name());

                $result[$camelName] = $property;
                $dbTypes[$camelName] = $this->getDbType($property->dataType());

                return $result;
            },
            []
        );

        parent::__construct(\compact('entity', 'item', 'name', 'actions', 'properties', 'dbTypes', 'pkName', 'fkFns'));
    }

    protected function getFileTemplate(): string
    {
        return 'MySQLGateway.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Gateway', parent::getPathTemplate());
    }

    private function getDbType(string $dataType): string
    {
        $dbTypes = [
            'array' => 'ARRAY',
            'bigint' => 'BIGINT',
            'binary' => 'BINARY',
            'blob' => 'BLOB',
            'boolean' => 'BOOLEAN',
            'date' => 'DATE_MUTABLE',
            'date_immutable' => 'DATE_IMMUTABLE',
            'dateinterval' => 'DATEINTERVAL',
            'datetime' => 'DATETIME_MUTABLE',
            'datetime_immutable' => 'DATETIME_IMMUTABLE',
            'datetimetz' => 'DATETIMETZ_MUTABLE',
            'datetimetz_immutable' => 'DATETIMETZ_IMMUTABLE',
            'decimal' => 'DECIMAL',
            'float' => 'FLOAT',
            'guid' => 'GUID',
            'integer' => 'INTEGER',
            'json' => 'JSON',
            'object' => 'OBJECT',
            'simple_array' => 'SIMPLE_ARRAY',
            'smallint' => 'SMALLINT',
            'text' => 'TEXT',
            'time' => 'TIME_MUTABLE',
            'time_immutable' => 'TIME_IMMUTABLE',
        ];

        if (!empty($dbTypes[$dataType])) {
            return $dbTypes[$dataType];
        }

        return 'STRING';
    }
}
