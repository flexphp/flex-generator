<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Template;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class TemplateBuilder extends AbstractBuilder
{
    private string $action;

    public function __construct(SchemaInterface $schema, string $action)
    {
        $this->action = $action;

        $route = $this->getInflector()->route($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $items = $this->getInflector()->items($schema->name());
        $fkRels = $this->getFkRelations($schema->fkRelations());
        $pkName = $this->getInflector()->camelProperty($schema->pkName());
        $properties = \array_reduce(
            $schema->attributes(),
            function (array $result, SchemaAttributeInterface $property) {
                $result[$this->getInflector()->camelProperty($property->name())] = $property;

                return $result;
            },
            []
        );

        parent::__construct(\compact('route', 'item', 'items', 'properties', 'fkRels', 'pkName'));
    }

    protected function getFileTemplate(): string
    {
        return $this->action . '.html.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/templates', parent::getPathTemplate());
    }
}
