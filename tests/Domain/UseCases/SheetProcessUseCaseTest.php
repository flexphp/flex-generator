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
        $request = new SheetProcessRequest($name, $path, $this->getOutputFolder());

        $useCase = new SheetProcessUseCase();
        $response = $useCase->execute($request);

        $this->assertFileExists($response->controller);
        \unlink($response->controller);

        $this->assertFileExists($response->constraint);
        \unlink($response->constraint);

        $this->assertFileExists($response->entity);
        \unlink($response->entity);

        foreach ($response->useCases as $useCase) {
            $this->assertFileExists($useCase);
            \unlink($useCase);
        }
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__)],
            ['Comments', \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__)],
        ];
    }
}
