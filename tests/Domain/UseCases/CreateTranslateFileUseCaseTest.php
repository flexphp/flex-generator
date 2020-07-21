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

use FlexPHP\Generator\Domain\Messages\Requests\CreateTranslateFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTranslateFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateTranslateFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class CreateTranslateFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, string $expectedFile): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateTranslateFileRequest($schema);

        $useCase = new CreateTranslateFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateTranslateFileResponse::class, $response);

        $filename = \explode('/', $response->file);
        $this->assertEquals($expectedFile, \array_pop($filename));
        $this->assertFileExists($response->file);

        \unlink($response->file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'post.en.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'comment.en.php'],
        ];
    }
}
