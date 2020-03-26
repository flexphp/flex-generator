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

class YamlWriter extends AbstractWriter
{
    public function __construct(array $data, string $filename)
    {
        $this->data = $data;
        $this->filename = $filename;
        $this->path = \sprintf('%1$s/../../tmp', __DIR__);
    }

    protected function getContent(): string
    {
        return Yaml::dump($this->data, 4, 2);
    }

    protected function getExtension(): string
    {
        return 'yaml';
    }
}
