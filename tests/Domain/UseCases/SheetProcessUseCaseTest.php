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

use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\UseCases\SheetProcessUseCase;
use FlexPHP\Generator\Tests\TestCase;

final class SheetProcessUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile
     */
    public function testItSymfony43Ok(string $name, string $path): void
    {
        $request = new SheetProcessRequest($name, $path);

        $useCase = new SheetProcessUseCase();
        $response = $useCase->execute($request);

        $this->assertFileExists($response->controller);
        \unlink($response->controller);

        $this->assertFileExists($response->entity);
        \unlink($response->entity);

        $this->assertFileExists($response->constraint);
        \unlink($response->constraint);

        $this->assertFileExists($response->translate);
        \unlink($response->translate);

        $this->assertFileExists($response->formType);
        \unlink($response->formType);

        $this->assertFileExists($response->gateway);
        \unlink($response->gateway);

        $this->assertFileExists($response->concreteGateway);
        \unlink($response->concreteGateway);

        $this->assertFileExists($response->factory);
        \unlink($response->factory);

        $this->assertFileExists($response->repository);
        \unlink($response->repository);

        $checkPatch = false;
        $countFiles = 5;
        $countCommands = 5;
        $countTemplates = 6;

        switch ($name) {
            case 'Posts':
                $this->assertNull($response->javascript);
                $this->assertNull($response->filterForm);

                break;
            case 'Comments':
                $countFiles = 6;

                $this->assertFileExists($response->javascript);
                \unlink($response->javascript);

                break;
            case 'Users':
                $countFiles = 6;

                $this->assertNull($response->javascript);

                break;
            case 'CustomActions':
                $countFiles = 2;
                $countCommands = 2;
                $countTemplates = 3;

                $this->assertNull($response->javascript);

                break;
            case 'Patch':
                $checkPatch = true;
                $countFiles = 2;
                $countCommands = 2;
                $countTemplates = 2;

                $this->assertNull($response->javascript);

                break;
            case 'Filter':
                $countFiles = 1;
                $countCommands = 1;
                $countTemplates = 2;

                $this->assertNull($response->javascript);

                $this->assertFileExists($response->filterForm);
                \unlink($response->filterForm);

                break;
        }

        $this->assertEquals($countFiles, \count($response->requests));

        \array_map(function (string $request) use ($checkPatch): void {
            $this->assertFileExists($request);

            if ($checkPatch && \strpos($request, 'Update') !== false) {
                $this->assertStringContainsString('$_patch = ', \file_get_contents($request));
            }

            \unlink($request);
        }, $response->requests);

        $this->assertEquals($countFiles, \count($response->responses));

        \array_map(function (string $response): void {
            $this->assertFileExists($response);
            \unlink($response);
        }, $response->responses);

        $this->assertEquals($countFiles, \count($response->useCases));

        \array_map(function (string $useCase): void {
            $this->assertFileExists($useCase);
            \unlink($useCase);
        }, $response->useCases);

        $this->assertEquals($countCommands, \count($response->commands));

        \array_map(function (string $command): void {
            $this->assertFileExists($command);
            \unlink($command);
        }, $response->commands);

        $this->assertEquals($countTemplates, \count($response->templates));

        \array_map(function (string $template): void {
            $this->assertFileExists($template);
            \unlink($template);
        }, $response->templates);
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__)],
            ['Comments', \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__)],
            ['Users', \sprintf('%1$s/../../Mocks/yaml/users.yaml', __DIR__)],
            ['CustomActions', \sprintf('%1$s/../../Mocks/yaml/customActions.yaml', __DIR__)],
            ['Patch', \sprintf('%1$s/../../Mocks/yaml/patch.yaml', __DIR__)],
            ['Filter', \sprintf('%1$s/../../Mocks/yaml/filter.yaml', __DIR__)],
        ];
    }
}
