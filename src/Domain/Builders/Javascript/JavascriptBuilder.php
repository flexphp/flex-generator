<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Javascript;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class JavascriptBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $route = $this->getInflector()->route($schema->name());
        $form = $this->getInflector()->form($schema->name());
        $fkRels = $this->getNotBlameFkRelations($schema);

        parent::__construct(\compact('route', 'form', 'fkRels'));
    }

    protected function getFileTemplate(): string
    {
        return 'Javascript.js.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Javascript', parent::getPathTemplate());
    }

    private function getNotBlameFkRelations(SchemaInterface $schema): array
    {
        $fkRels = $this->getFkRelations($schema->fkRelations());
        $properties = \array_filter(\array_map(function (SchemaAttributeInterface $property) {
            if ($property->isBlameBy()) {
                return $property->name();
            }
        }, $schema->attributes()));


        return \array_filter($fkRels, function (array $fkRel) use ($properties) {
            if (!\in_array($fkRel['pk'], $properties)) {
                return $fkRel;
            }
        });
    }
}
