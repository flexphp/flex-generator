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

final class ProcessFormatUseCaseTest extends TestCase
{
    /**
     * @dataProvider getPathNotValid
     *
     * @param mixed $path
     */
    public function testItFormatPathNotValidThrowException($path): void
    {
        $this->expectException(FormatPathNotValidException::class);

        $request = new ProcessFormatRequest($path, 'filename', 'xlsx');

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

        $request = new ProcessFormatRequest(
            \sprintf('%1$s/../../../src/dist/templates/Format.xlsx', __DIR__),
            'filename',
            'doc'
        );

        $useCase = new ProcessFormatUseCase();
        $useCase->execute($request);
    }

    public function testItFormatOk(): void
    {
        $name = 'Format';
        $extension = 'xlsx';
        $request = new ProcessFormatRequest(
            \sprintf('%s/../../../src/dist/templates/%s.%s', __DIR__, $name, $extension),
            $name,
            $extension
        );

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
        }

        $zip = \sprintf('%1$s/../../../src/tmp/%2$s.zip', __DIR__, $name);

        $this->assertFileExists($zip);
        $this->assertTrue(\filesize($zip) > 0);

        \unlink($zip);
    }
}
