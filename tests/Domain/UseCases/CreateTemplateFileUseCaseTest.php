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

use FlexPHP\Generator\Domain\Messages\Requests\CreateTemplateFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTemplateFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateTemplateFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class CreateTemplateFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $schemafile, array $expectedFiles, string $expectedDir): void
    {
        $schema = Schema::fromFile($schemafile);

        $request = new CreateTemplateFileRequest($schema);

        $useCase = new CreateTemplateFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateTemplateFileResponse::class, $response);
        $this->assertEquals(5, \count($response->files));

        foreach ($response->files as $index => $file) {
            $filename = \explode('/', $file);
            $this->assertEquals($expectedFiles[$index], \array_pop($filename));
            $this->assertEquals($expectedDir, \array_pop($filename));
            $this->assertFileExists($file);

            \unlink($file);
        }
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), [
                'index.html.twig',
                'new.html.twig',
                'show.html.twig',
                'edit.html.twig',
                '_delete_form.html.twig',
            ], 'post'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), [
                'index.html.twig',
                'new.html.twig',
                'show.html.twig',
                'edit.html.twig',
                '_delete_form.html.twig',
            ], 'comment'],
            [\sprintf('%1$s/../../Mocks/yaml/userStatus.yaml', __DIR__), [
                'index.html.twig',
                'new.html.twig',
                'show.html.twig',
                'edit.html.twig',
                '_delete_form.html.twig',
            ], 'userStatus'],
        ];
    }
}
