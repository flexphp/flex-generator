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
