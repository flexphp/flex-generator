<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\UseCases\SheetProcessUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class SheetProcessUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException()
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new SheetProcessUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     * @param string $name
     * @param string $path
     * @return void
     */ 
    public function testItSymfony43Ok(string $name, string $path): void
    {
        $request = new SheetProcessRequest($name, $path);

        $useCase = new SheetProcessUseCase();
        $useCase->execute($request);
        
        $controller = \sprintf('%1$s/../../../src/tmp/skeleton/src/Controllers/%2$sController.php', __DIR__, $name);
        $this->assertFileExists($controller);
        \unlink($controller);
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__)],
            ['Comments', \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__)],
        ];
    }
}
