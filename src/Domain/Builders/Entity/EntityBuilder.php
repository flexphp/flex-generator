<?php

namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class EntityBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $name = $data['name'];
        $properties = $data['properties'];
        $_properties = array_keys($data['properties']);

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

    private function getGetters($properties): array
    {
        $getters = [];

        foreach ($properties as $name => $attributes) {
            $getters[$name] = new GetterBuilder([
                $name => [
                    'type' => $attributes['type'],
                ]
            ]);
        }

        return $getters;
    }

    private function getSetters($properties): array
    {
        $setters = [];

        foreach ($properties as $name => $attributes) {
            $setters[$name] = new SetterBuilder([
                $name => [
                    'type' => $attributes['type'],
                ]
            ]);
        }

        return $setters;
    }
}
