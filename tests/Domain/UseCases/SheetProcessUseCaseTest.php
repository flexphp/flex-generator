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

use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\UseCases\SheetProcessUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class SheetProcessUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new SheetProcessUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $name, string $path): void
    {
        $request = new SheetProcessRequest($name, $path);

        $useCase = new SheetProcessUseCase();
        $response = $useCase->execute($request);

        $this->assertFileExists($response->controller);
        \unlink($response->controller);

        $this->assertFileExists($response->entity);
        \unlink($response->entity);

        $this->assertFileExists($response->constraint);
        \unlink($response->constraint);

        $this->assertEquals(5, \count($response->requests));
        \array_map(function (string $request): void {
            $this->assertFileExists($request);
            \unlink($request);
        }, $response->requests);

        $this->assertEquals(5, \count($response->responses));
        \array_map(function (string $response): void {
            $this->assertFileExists($response);
            \unlink($response);
        }, $response->responses);

        $this->assertEquals(5, \count($response->useCases));
        \array_map(function (string $useCase): void {
            $this->assertFileExists($useCase);
            \unlink($useCase);
        }, $response->useCases);

        $this->assertEquals(5, \count($response->commands));
        \array_map(function (string $command): void {
            $this->assertFileExists($command);
            \unlink($command);
        }, $response->commands);
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__)],
            ['Comments', \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__)],
        ];
    }
}
