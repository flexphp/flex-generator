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

final class YamlWriter extends AbstractWriter
{
    public function __construct(array $data, string $filename, string $path)
    {
        parent::__construct(Yaml::dump($data, 4, 2), $filename, $path);
    }

    protected function getExtension(): string
    {
        return 'yaml';
    }
}
