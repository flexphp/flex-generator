<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\MakeControllerRequest;
use FlexPHP\Generator\Domain\Messages\Responses\MakeControllerResponse;
use FlexPHP\Generator\Domain\UseCases\MakeControllerUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class MakeControllerUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException()
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new MakeControllerUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     * @param string $entity
     * @param array $actions
     * @return void
     */ 
    public function testItSymfony43Ok(string $entity, array $actions): void
    {
        $request = new MakeControllerRequest($entity, $actions);

        $useCase = new MakeControllerUseCase();
        $response = $useCase->execute($request);
        
        $this->assertInstanceOf(MakeControllerResponse::class, $response);
        $file = $response->file;
        $this->assertFileExists($file);
        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', [
                'index',
                'create',
                'read',
                'update',
                'delete',
            ]],
            ['Comments', [
                'create',
                'read',
                'update',
            ]],
        ];
    }
}
