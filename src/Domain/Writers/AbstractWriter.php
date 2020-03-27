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
        $path = $this->getPath();

        if (!\is_dir($path)) {
            \mkdir($path, 0777, true); // @codeCoverageIgnore
        }

        $output = \sprintf('%1$s/%2$s.%3$s', $path, $this->getFilename(), $this->getExtension());

        \file_put_contents($output, $this->getContent());

        return $output;
    }

    abstract protected function getExtension(): string;

    private function getContent(): string
    {
        return $this->content;
    }

    private function getFilename(): string
    {
        return $this->filename;
    }

    private function getPath(): string
    {
        return $this->path;
    }
}
