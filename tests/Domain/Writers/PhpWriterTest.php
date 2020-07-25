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

use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Generator\Tests\TestCase;

final class PhpWriterTest extends TestCase
{
    public function testItSaveOk(): void
    {
        $filename = 'file';
        $content = 'Hello world!';
        $path = __DIR__;

        $writer = new PhpWriter($content, $filename, $path);
        $output = $writer->save();

        $_filename = \explode('/', $output);
        $this->assertEquals(\array_pop($_filename), $filename . '.php');
        $this->assertFileExists(\sprintf('%s%s%s.php', $path, \DIRECTORY_SEPARATOR, $filename));
        $this->assertEquals(<<<T
$content
T
, \file_get_contents($output));

        \unlink($output);
    }
}
