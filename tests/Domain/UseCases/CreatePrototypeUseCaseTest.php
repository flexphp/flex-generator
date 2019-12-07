<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\UseCases\CreatePrototypeUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class CreatePrototypeUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException()
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreatePrototypeUseCase();
        $useCase->execute(null);
    }

    /**
     * @return void
     */ 
    public function testItWithSymfony(): void
    {
        $sheets = [
            'Posts' => \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
            'Comments' => \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
        ];

        $request = new CreatePrototypeRequest($sheets);

        $useCase = new CreatePrototypeUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreatePrototypeResponse::class, $response);

        $this->deleteFolder(__DIR__ . '/../../../src/tmp/skeleton/', false);
    }

    private function deleteFolder($dir, $delete = true)
    {
        if (is_dir($dir)) {
            $objects = array_diff(scandir($dir), ['.', '..']);

            foreach ($objects as $object) {
                if (is_dir($dir . '/' . $object) && !is_link($dir . '/' . $object)) {
                    $this->deleteFolder($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }

            if ($delete) {
                rmdir($dir);
            }
        }
    }
}
