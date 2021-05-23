<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class EntityBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $name = $this->getInflector()->entity($schema->name());
        $getters = $this->getGetters($schema->attributes());
        $setters = $this->getSetters($schema->attributes());
        $fkGetters = $this->getFkGetters($schema->name(), $schema->fkRelations());
        $fkSetters = $this->getFkSetters($schema->name(), $schema->fkRelations());
        $_properties = $this->getProperties($schema->attributes());
        $defaults = $this->getDefaults($schema->attributes());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $fkRels = $this->getFkRelations($schema->fkRelations());
        $header = self::getHeaderFile();

        parent::__construct(
            \compact(
                'header',
                'name',
                'getters',
                'fkGetters',
                'setters',
                'fkSetters',
                '_properties',
                'fkFns',
                'fkRels',
                'defaults'
            )
        );
    }

    protected function getFileTemplate(): string
    {
        return 'Entity.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    private function getProperties(array $properties): array
    {
        return \array_values(
            \array_reduce(
                $properties,
                function (array $result, SchemaAttributeInterface $attribute): array {
                    $result[] = $this->getInflector()->camelProperty($attribute->name());

                    return $result;
                },
                []
            )
        );
    }

    private function getDefaults(array $properties): array
    {
        return \array_reduce(
            $properties,
            function (array $result, SchemaAttributeInterface $attribute): array {
                if ($attribute->default() !== null) {
                    $name = $this->getInflector()->camelProperty($attribute->name());
                    $result[$name] = [
                        'type' => $attribute->typeHint(),
                        'value' => $attribute->default(),
                    ];

                    return $result;
                }

                return $result;
            },
            []
        );
    }

    private function getGetters(array $properties): array
    {
        return \array_reduce(
            $properties,
            function (array $result, SchemaAttributeInterface $attribute): array {
                $result[] = new GetterBuilder($attribute);

                return $result;
            },
            []
        );
    }

    private function getSetters(array $properties): array
    {
        return \array_reduce(
            $properties,
            function (array $result, SchemaAttributeInterface $attribute): array {
                $result[] = new SetterBuilder($attribute);

                return $result;
            },
            []
        );
    }

    private function getFkGetters(string $table, array $fkRelations): array
    {
        return \array_reduce(
            $fkRelations,
            function (array $result, array $fkRel) use ($table): array {
                $result[] = new FkGetterBuilder(
                    $fkRel['pkId'],
                    $fkRel['pkTable'] === $table ? 'self' : $fkRel['pkTable'],
                    $fkRel['isRequired']
                );

                return $result;
            },
            []
        );
    }

    private function getFkSetters(string $table, array $fkRelations): array
    {
        return \array_reduce(
            $fkRelations,
            function (array $result, array $fkRel) use ($table): array {
                $result[] = new FkSetterBuilder(
                    $fkRel['pkId'],
                    $fkRel['pkTable'] === $table ? 'self' : $fkRel['pkTable'],
                    $fkRel['isRequired'],
                    $table
                );

                return $result;
            },
            []
        );
    }
}
