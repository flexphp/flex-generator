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

use FlexPHP\Generator\Domain\Messages\Requests\CreateControllerFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateControllerFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateControllerFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class CreateControllerFileUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreateControllerFileUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, string $expectedFile): void
    {
        $schema = Schema::fromFile($schemafile);
        $actions = [
            'index',
            'create',
            'update',
            'read',
            'delete',
        ];

        $request = new CreateControllerFileRequest($schema->name(), $actions);

        $useCase = new CreateControllerFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateControllerFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals($expectedFile, \array_pop($filename));
        $this->assertFileExists($file);
        $content = \file_get_contents($file);

        foreach ($actions as $action) {
            $this->assertStringContainsString($action, $content);
        }

        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'PostController.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'CommentController.php'],
        ];
    }
}
