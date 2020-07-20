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
    public function __construct(string $name, SchemaInterface $schema)
    {
        $getters = [];
        $setters = [];
        $_properties = [];
        $name = $this->getPascalCase($this->getSingularize($name));

        if ($schema) {
            $getters = $this->getGetters($schema->attributes());
            $setters = $this->getSetters($schema->attributes());
            $_properties = $this->getProperties($schema->attributes());
        }

        parent::__construct(\compact('name', 'getters', 'setters', '_properties'));
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
                function (array $result, SchemaAttributeInterface $attributes): array {
                    $result[] = $this->getCamelCase($attributes->name());

                    return $result;
                },
                []
            )
        );
    }

    private function getGetters(array $properties): array
    {
        return \array_reduce(
            $properties,
            function (array $result, SchemaAttributeInterface $attributes): array {
                $result[] = new GetterBuilder($attributes);

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
}
