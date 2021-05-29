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

use FlexPHP\Generator\Domain\Messages\Requests\CreateFormTypeFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateFormTypeFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateFilterFormFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class CreateFilterFormFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItOk(string $schemafile, string $expectedFile): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateFormTypeFileRequest($schema);

        $useCase = new CreateFilterFormFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateFormTypeFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals($expectedFile, \array_pop($filename));
        $this->assertFileExists($file);

        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'PostFilterFormType.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'CommentFilterFormType.php'],
        ];
    }
}
