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

use FlexPHP\Generator\Domain\Messages\Requests\CreateMenuFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateMenuFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateMenuFileUseCase;
use FlexPHP\Generator\Tests\TestCase;

final class CreateMenuFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFiles()
     *
     * @param array $schemafiles
     */
    public function testItOk(array $schemafiles): void
    {
        $request = new CreateMenuFileRequest($schemafiles);

        $useCase = new CreateMenuFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateMenuFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals('menu.php', \array_pop($filename));
        $this->assertFileExists($file);

        \unlink($file);
    }

    public function getEntityFiles(): array
    {
        return [
            [[
                \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
                \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
            ]],
        ];
    }
}
