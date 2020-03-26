<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Writers;

abstract class AbstractWriter implements WriterInterface
{
    private $content;

    private $filename;

    private $path;

    public function __construct(string $content, string $filename, string $path)
    {
        $this->content = $content;
        $this->filename = $filename;
        $this->path = $path;
    }

    public function save(): string
    {
        $output = \sprintf('%1$s/%2$s.%3$s', $this->getPath(), $this->getFilename(), $this->getExtension());

        \file_put_contents($output, $this->getContent());

        return $output;
    }

    abstract protected function getExtension(): string;

    protected function getContent(): string
    {
        return $this->content;
    }

    protected function getFilename(): string
    {
        return $this->filename;
    }

    protected function getPath(): string
    {
        return $this->path;
    }
}
