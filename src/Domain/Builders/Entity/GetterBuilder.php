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

final class GetterBuilder extends AbstractBuilder
{
    public function __construct(SchemaAttributeInterface $property)
    {
        $name = $this->getInflector()->camelProperty($property->name());
        $typehint = $property->typeHint();
        $required = $property->isRequired();

        parent::__construct(\compact('name', 'typehint', 'required'));
    }

    protected function getFileTemplate(): string
    {
        return 'Getter.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }
}
