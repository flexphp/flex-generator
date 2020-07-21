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
        $entity = $this->getPascalCase($this->getSingularize($schema->name()));
        $item = $this->getCamelCase($this->getSingularize($entity));
        $setters = $this->getSetterWithCasting($schema->attributes());

        parent::__construct(\compact('entity', 'item', 'setters'));
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
                'pascal' => $this->getPascalCase($attributes->name()),
                'camel' => $this->getCamelCase($attributes->name()),
                'typehint' => $attributes->typeHint(),
            ];

            return $result;
        }, []);
    }
}
