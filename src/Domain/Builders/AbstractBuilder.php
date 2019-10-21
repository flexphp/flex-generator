<?php

namespace FlexPHP\Generator\Domain\Builders;

abstract class AbstractBuilder implements BuilderInterface
{
    private $data;
    private $config;

    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['action'])) {
            $data['action_name'] = $this->camelCase($data['action']);
        }

        $this->data = $data;
        $this->config = $config;
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/../BoilerPlates', __DIR__);
    }

    public function build(): string
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->getPathTemplate());
        $twig = new \Twig\Environment($loader);

        return $twig->render($this->getFileTemplate(), $this->data);
    }

    public function __toString()
    {
        return $this->build();
    }

    protected function camelCase(string $string): string
    {
        return str_replace(' ', '', \ucwords(\str_replace('_', ' ', $string)));
    }
}
