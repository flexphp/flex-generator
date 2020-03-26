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

use FlexPHP\Generator\Domain\Messages\Requests\CreateRequestFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateRequestFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class CreateRequestFileUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreateRequestFileUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, array $expectedFiles): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateRequestFileRequest($schema->name(), $schema->attributes(), [
            'C' => 'create',
            'U' => 'update',
        ]);

        $useCase = new CreateRequestFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateRequestFileResponse::class, $response);

        foreach ($response->files as $index => $file) {
            $filename = \explode('/', $file);
            $this->assertEquals($expectedFiles[$index], \array_pop($filename));
            $this->assertFileExists($file);
            \unlink($file);
        }
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), [
                'CreatePostRequest.php',
                'UpdatePostRequest.php',
            ]],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), [
                'CreateCommentRequest.php',
                'UpdateCommentRequest.php',
            ]],
        ];
    }
}