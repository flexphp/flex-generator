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

        $data = [
            'SheetName' => [
                'Name' => 'foo',
                'DataType' => 'bar',
            ],
        ];

        $writer = new YamlWriter($data, $filename);
        $output = $writer->save();

        $_filename = \explode('/', $output);
        $this->assertEquals(\array_pop($_filename), $filename . '.yaml');
        $this->assertFileExists($output);
        \unlink($output);
    }
}
