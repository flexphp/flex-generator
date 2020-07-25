<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Writers;

use FlexPHP\Generator\Domain\Writers\YamlWriter;
use FlexPHP\Generator\Tests\TestCase;

final class YamlWriterTest extends TestCase
{
    public function testItSaveOk(): void
    {
        $filename = 'Test';
        $data = ['SheetName' => []];
        $path = __DIR__;

        $writer = new YamlWriter($data, $filename, $path);
        $output = $writer->save();

        $_filename = \explode('/', $output);
        $this->assertEquals(\array_pop($_filename), $filename . '.yaml');
        $this->assertFileExists(\sprintf('%s%s%s.yaml', $path, \DIRECTORY_SEPARATOR, $filename));
        $this->assertEquals(<<<T
SheetName: {  }
T
, \rtrim(\file_get_contents($output)));

        \unlink($output);
    }
}
