<?php

namespace FlexPHP\Generator\Domain\Builders;

use Jawira\CaseConverter\Convert;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var array<string|array>
     */
    private $data;

    /**
     * @var array[]
     */
    private $config;

    /**
     * @param array<string|array> $data
     * @param array[] $config
     */
    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['action']) && is_string($data['action'])) {
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

    protected function getCamelCase(string $string): string
    {
        return (new Convert($string))->toCamel();
    }

    protected function getSnakeCase(string $string): string
    {
        return (new Convert($string))->toSnake();
    }
}
