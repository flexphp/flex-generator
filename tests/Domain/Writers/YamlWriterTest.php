<?php

namespace FlexPHP\Generator\Tests\Domain\Writers;

use FlexPHP\Generator\Domain\Writers\YamlWriter;
use FlexPHP\Generator\Tests\TestCase;

class YamlWriterTest extends TestCase
{
    public function testItSaveOk()
    {
        $filename = 'Test';

        $data = [
            'SheetName' => [
                'Name' => 'foo',
                'DataType' => 'bar',
            ]
        ];

        $writer = new YamlWriter($data, $filename);
        $output = $writer->save();

        $this->assertFileExists($output);

        \unlink($output);
    }
}
