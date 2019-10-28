<?php

namespace FlexPHP\Generator\Domain\Writers;

use Symfony\Component\Yaml\Yaml;

class YamlWriter implements WriterInterface
{
    private $data;
    private $filename;
    /** @var string */
    private $path;

    public function __construct(array $data, string $filename)
    {
        $this->data = $data;
        $this->filename = $filename;
        $this->path = \sprintf('%1$s/../../tmp', __DIR__);
    }

    public function save(): string
    {
        $output = \sprintf('%1$s/%2$s.yaml', $this->path, $this->filename);

        \file_put_contents($output, Yaml::dump($this->data, 4, 2));

        return $output;
    }
}
