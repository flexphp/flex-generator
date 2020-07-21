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

final class CreateUseCaseFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItOk(string $schemafile, array $expectedFiles): void
    {
        $schema = Schema::fromFile($schemafile);
        $actions = [
            'R' => 'read',
            'D' => 'delete',
        ];

        $request = new CreateUseCaseFileRequest($schema, $actions);

        $useCase = new CreateUseCaseFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateUseCaseFileResponse::class, $response);
        $this->assertEquals(\count($actions), \count($response->files));

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
                'ReadPostUseCase.php',
                'DeletePostUseCase.php',
            ]],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), [
                'ReadCommentUseCase.php',
                'DeleteCommentUseCase.php',
            ]],
        ];
    }
}
