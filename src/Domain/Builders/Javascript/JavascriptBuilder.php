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
use FlexPHP\Schema\SchemaInterface;

final class JavascriptBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $route = $this->getPluralize($this->getDashCase($schema->name()));
        $form = $this->getSnakeCase($this->getSingularize($schema->name()));
        $fkRels = $this->getFkRelations($schema->fkRelations());

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
}
