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

use FlexPHP\Generator\Domain\Writers\SqlWriter;
use FlexPHP\Generator\Tests\TestCase;

final class SqlWriterTest extends TestCase
{
    public function testItSaveOk(): void
    {
        $filename = 'script';
        $content = '-- my databse content go here';
        $path = __DIR__;

        $writer = new SqlWriter($content, $filename, $path);
        $output = $writer->save();

        $_filename = \explode('/', $output);
        $this->assertEquals(\array_pop($_filename), $filename . '.sql');
        $this->assertFileExists($output);
        $this->assertEquals(<<<T
$content
T
, \file_get_contents($output));

        \unlink($output);
    }
}
