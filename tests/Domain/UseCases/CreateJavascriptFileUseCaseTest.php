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

use FlexPHP\Generator\Domain\Messages\Requests\CreateJavascriptFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateJavascriptFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateJavascriptFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class CreateJavascriptFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, string $expectedFile): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateJavascriptFileRequest($schema);

        $useCase = new CreateJavascriptFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateJavascriptFileResponse::class, $response);

        $file = $response->file;
        $filename = \explode('/', $response->file);
        $this->assertEquals($expectedFile, \array_pop($filename));
        $this->assertFileExists($file);

        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'posts.js'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'comments.js'],
            [\sprintf('%1$s/../../Mocks/yaml/userStatus.yaml', __DIR__), 'userStatus.js'],
        ];
    }
}
