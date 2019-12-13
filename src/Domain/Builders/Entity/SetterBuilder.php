<?php

namespace FlexPHP\Generator\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class SetterBuilder extends AbstractBuilder
{
    use TypeHintTrait;

    /**
     * @param array[] $data
     * @param array $config
     */
    public function __construct(array $data, array $config = [])
    {
        $name = (string)array_key_first($data);
        $typehint = $this->guessTypeHint($data[$name]);
        $setter = $this->getPascalCase($name);

        $_data = \compact('name', 'typehint', 'setter');

        parent::__construct($_data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Setter.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }
}
