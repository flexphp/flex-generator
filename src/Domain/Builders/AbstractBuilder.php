<?php

namespace FlexPHP\Generator\Domain\Builders;

use Jawira\CaseConverter\Convert;
use Twig\Extra\String\StringExtension;

abstract class AbstractBuilder implements BuilderInterface
{
    private $data;
    private $config;

    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['action'])) {
            $data['action_name'] = $this->getPascalCase($data['action']);
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
        $twig->addExtension(new StringExtension());

        return $twig->render($this->getFileTemplate(), $this->data);
    }

    public function __toString()
    {
        return $this->build();
    }

    protected function getPascalCase(string $string): string
    {
        return (new Convert($string))->toPascal();
    }

    protected function getSnakeCase(string $string): string
    {
        return (new Convert($string))->toSnake();
    }
}
