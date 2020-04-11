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
use FlexPHP\Schema\Constants\Keyword;

final class MySQLGatewayBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $actions, array $properties)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $name = $this->getSnakeCase($this->getPluralize($entity));
        $item = $this->getCamelCase($this->getSingularize($entity));
        $actions = \array_reduce($actions, function (array $result, string $action) {
            $result[] = $this->getCamelCase($action);

            return $result;
        }, []);

        $properties = \array_reduce($properties, function ($result, $property) {
            $result[$this->getCamelCase($property[Keyword::NAME])] = $property;

            return $result;
        }, []);

        $dbtypes = \array_reduce($properties, function ($result, $property) {
            $result[$this->getCamelCase($property[Keyword::NAME])] = $this->getDbType($property[Keyword::DATATYPE]);

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'item', 'name', 'actions', 'properties', 'dbtypes'));
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
            'smallint' => 'INTEGER',
            'integer' => 'INTEGER',
            'float' => 'INTEGER',
            'double' => 'INTEGER',
            'bool' => 'BOOLEAN',
            'boolean' => 'BOOLEAN',
        ];

        if (!empty($dbTypes[$dataType])) {
            return $dbTypes[$dataType];
        }

        return 'STRING';
    }
}
