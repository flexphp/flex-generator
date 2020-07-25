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

use FlexPHP\Generator\Domain\Writers\JsWriter;
use FlexPHP\Generator\Tests\TestCase;

final class JsWriterTest extends TestCase
{
    public function testItSaveOk(): void
    {
        $filename = 'file';
        $content = 'Hello javascript!';
        $path = __DIR__;

        $writer = new JsWriter($content, $filename, $path);
        $output = $writer->save();

        $_filename = \explode('/', $output);
        $this->assertEquals(\array_pop($_filename), $filename . '.js');
        $this->assertFileExists(\sprintf('%s%s%s.js', $path, \DIRECTORY_SEPARATOR, $filename));
        $this->assertEquals(<<<T
$content
T
, \file_get_contents($output));

        \unlink($output);
    }
}
