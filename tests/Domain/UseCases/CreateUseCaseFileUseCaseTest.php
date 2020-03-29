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

use FlexPHP\Generator\Domain\Messages\Requests\CreateUseCaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateUseCaseFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateUseCaseFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class CreateUseCaseFileUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreateUseCaseFileUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, string $action, string $expectedFile): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateUseCaseFileRequest($schema->name(), $action, $schema->attributes(), $this->getOutputFolder());

        $useCase = new CreateUseCaseFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateUseCaseFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals($expectedFile, \array_pop($filename));
        $this->assertFileExists($file);
        $content = \file_get_contents($file);

        foreach ($schema->attributes() as $attribute) {
            $this->assertStringContainsStringIgnoringCase($attribute->name(), $content);
        }

        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'index', 'IndexPostUseCase.php'],
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'create', 'CreatePostUseCase.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'update', 'UpdateCommentUseCase.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'delete', 'DeleteCommentUseCase.php'],
        ];
    }
}
