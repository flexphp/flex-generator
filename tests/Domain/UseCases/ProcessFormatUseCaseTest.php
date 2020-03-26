<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Exceptions\FormatPathNotValidException;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class ProcessFormatUseCaseTest extends TestCase
{
    public function testItFormatNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new ProcessFormatUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getPathNotValid
     *
     * @param mixed $path
     */
    public function testItFormatPathNotValidThrowException($path): void
    {
        $this->expectException(FormatPathNotValidException::class);

        $request = new ProcessFormatRequest($path, 'xlsx');

        $useCase = new ProcessFormatUseCase();
        $useCase->execute($request);
    }

    public function getPathNotValid(): array
    {
        return [
            [''],
            ['/path/not/exist'],
        ];
    }

    public function testItFormatNotSupportedThrowException(): void
    {
        $this->expectException(FormatNotSupportedException::class);

        $request = new ProcessFormatRequest(\sprintf('%1$s/../../../src/dist/templates/Format.xlsx', __DIR__), 'doc');

        $useCase = new ProcessFormatUseCase();
        $useCase->execute($request);
    }

    public function testItFormatOk(): void
    {
        $request = new ProcessFormatRequest(\sprintf('%1$s/../../../src/dist/templates/Format.xlsx', __DIR__), 'xlsx');

        $useCase = new ProcessFormatUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(ProcessFormatResponse::class, $response);
        $sheetNames = $response->messages;
        $this->assertEquals(2, \count($sheetNames));

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
