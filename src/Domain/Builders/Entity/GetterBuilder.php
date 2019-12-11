<?php

namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class GetterBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $name = array_key_first($data);
        $typehint = $this->guessTypeHint($data[$name]);
        $getter = $this->getPascalCase($name);

        $_data = \compact('name', 'typehint', 'getter');

        parent::__construct($_data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Getter.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }

    private function guessTypeHint(array $properties): string
    {
        $typeHint = 'string';
        $dataType = $properties['type'] ?? 'string';

        switch ($dataType) {
            case 'smallint':
            case 'integer':
            case 'bigint':
            case 'decimal':
                $typeHint = 'int';
                break;
            case 'float':
                $typeHint = 'float';
                break;
            case 'bool':
            case 'boolean':
                $typeHint = 'bool';
                break;
            case 'array':
            case 'simple_array':
            case 'json_array':
                $typeHint = 'array';
                break;
            // Only 7.2
            // case 'object':
                // $typeHint = 'object';
                // break;
        }

        return $typeHint;
    }
}
