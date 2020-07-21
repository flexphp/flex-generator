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

use FlexPHP\Generator\Domain\Messages\Requests\CreateCommandFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateCommandFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateCommandFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class CreateCommandFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, array $expectedFiles): void
    {
        $schema = Schema::fromFile($schemafile);
        $actions = [
            'R' => 'read',
            'D' => 'delete',
        ];

        $request = new CreateCommandFileRequest($schema, $actions);

        $useCase = new CreateCommandFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateCommandFileResponse::class, $response);
        $this->assertEquals(\count($actions), \count($response->files));

        foreach ($response->files as $index => $file) {
            $filename = \explode('/', $file);
            $this->assertEquals($expectedFiles[$index], \array_pop($filename));
            $this->assertFileExists($file);
            $content = \file_get_contents($file);

            foreach ($schema->attributes() as $attribute) {
                $this->assertStringContainsStringIgnoringCase($attribute->name(), $content);
            }

            \unlink($file);
        }
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), [
                'ReadPostCommand.php',
                'DeletePostCommand.php',
            ]],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), [
                'ReadCommentCommand.php',
                'DeleteCommentCommand.php',
            ]],
        ];
    }
}
