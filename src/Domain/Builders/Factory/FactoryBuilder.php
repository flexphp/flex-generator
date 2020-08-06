<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Factory;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class FactoryBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $setters = $this->getSetterWithCasting($schema->attributes());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $fkRels = $this->getFkRelations($schema->fkRelations());

        parent::__construct(\compact('entity', 'item', 'setters', 'fkFns', 'fkRels'));
    }

    protected function getFileTemplate(): string
    {
        return 'Factory.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Factory', parent::getPathTemplate());
    }

    private function getSetterWithCasting(array $properties): array
    {
        return \array_reduce($properties, function (array $result, SchemaAttributeInterface $attributes): array {
            $result[] = [
                'pascal' => $this->getInflector()->pascalProperty($attributes->name()),
                'camel' => $this->getInflector()->camelProperty($attributes->name()),
                'typehint' => $attributes->typeHint(),
            ];

            return $result;
        }, []);
    }
}
