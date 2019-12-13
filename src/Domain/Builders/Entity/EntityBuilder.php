<?php

namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class EntityBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $name = $data['name'];
        $properties = !empty($data['properties']) && \is_array($data['properties'])
            ? $data['properties']
            : [];

        $_properties = \array_keys($properties);

        $getters = $this->getGetters($properties);
        $setters = $this->getSetters($properties);

        parent::__construct(\compact('name', '_properties', 'getters', 'setters'), $config);
    }

    public function getFileTemplate(): string
    {
        return 'Entity.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    private function getGetters(array $properties): array
    {
        $getters = [];

        foreach ($properties as $name => $attributes) {
            $getters[$name] = new GetterBuilder([
                $name => $attributes
            ]);
        }

        return $getters;
    }

    private function getSetters(array $properties): array
    {
        $setters = [];

        foreach ($properties as $name => $attributes) {
            $setters[$name] = new SetterBuilder([
                $name => $attributes
            ]);
        }

        return $setters;
    }
}
