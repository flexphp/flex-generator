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
use FlexPHP\Schema\Constants\Keyword;

final class EntityBuilder extends AbstractBuilder
{
    public function __construct(string $name, array $properties)
    {
        $_properties = \array_keys($properties);

        $getters = $this->getGetters($properties);
        $setters = $this->getSetters($properties);

        parent::__construct(\compact('name', '_properties', 'getters', 'setters'));
    }

    protected function getFileTemplate(): string
    {
        return 'Entity.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    private function getGetters(array $properties): array
    {
        $getters = [];

        foreach ($properties as $name => $attributes) {
            $getters[$name] = new GetterBuilder($name, $attributes[Keyword::DATATYPE]);
        }

        return $getters;
    }

    private function getSetters(array $properties): array
    {
        $setters = [];

        foreach ($properties as $name => $attributes) {
            $setters[$name] = new SetterBuilder($name, $attributes[Keyword::DATATYPE]);
        }

        return $setters;
    }
}
