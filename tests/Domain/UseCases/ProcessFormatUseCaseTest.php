<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class ProcessFormatUseCaseTest extends TestCase
{
    public function testItFormatNotValidRequestThrowException()
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new ProcessFormatUseCase();
        $useCase->execute(null);
    }

    public function testItFormatNotSupportedThrowException()
    {
        $this->expectException(FormatNotSupportedException::class);

        $request = new ProcessFormatRequest('/fake/path/file.doc', 'doc');

        $useCase = new ProcessFormatUseCase();
        $useCase->execute($request);
    }

    public function testItFormatOk()
    {
        $request = new ProcessFormatRequest(\sprintf('%1$s/../../../src/templates/Format.xlsx', __DIR__), 'xlsx');

        $useCase = new ProcessFormatUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(ProcessFormatResponse::class, $response);
        $sheetNames = $response->messages;
        $this->assertEquals(2, count($sheetNames));

        foreach ($sheetNames as $sheetName => $numberFields) {
            $numberExpected = 0;
            
            switch ($sheetName) {
                case 'Posts':
                    $numberExpected = 6;
                    break;
                case 'Comments':
                    $numberExpected = 5;
                    break;
                default:
                    $this->assertTrue(false, 'SheetName unknown: ' . $sheetName);
                    break;
            }

            $this->assertEquals($numberExpected, $numberFields);

            $yaml = \sprintf('%1$s/../../../src/tmp/%2$s.yaml', __DIR__, \strtolower($sheetName));

            $this->assertFileExists($yaml);

            $yamlContent = \file_get_contents($yaml);

            $this->assertContains('Entity', $yamlContent);
            $this->assertContains('Attributes', $yamlContent);

            \unlink($yaml);
        }
    }
}
