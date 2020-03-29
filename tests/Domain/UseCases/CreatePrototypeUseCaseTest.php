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

use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\UseCases\CreatePrototypeUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

final class CreatePrototypeUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreatePrototypeUseCase();
        $useCase->execute(null);
    }

    public function testItWithSymfony(): void
    {
        $sheets = [
            'Posts' => \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
            'Comments' => \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
        ];

        $request = new CreatePrototypeRequest($sheets, $this->getOutputFolder());

        $useCase = new CreatePrototypeUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreatePrototypeResponse::class, $response);
        $this->assertFileExists($response->outputFolder . '/composer.json');

        $this->assertDirectoryExists($response->outputFolder . '/src');
        $this->assertDirectoryExists($response->outputFolder . '/src/Controllers');
        $this->assertFileExists($response->outputFolder . '/src/Controllers/PostController.php');

        $this->assertDirectoryExists($response->outputFolder . '/Domain');
        $this->assertDirectoryExists($response->outputFolder . '/Domain/Post');
        $this->assertDirectoryExists($response->outputFolder . '/Domain/Post/Request');
        // $this->assertDirectoryExists($response->outputFolder . '/Domain/Post/Response');
        $this->assertDirectoryExists($response->outputFolder . '/Domain/Post/UseCase');

        $this->assertFileExists($response->outputFolder . '/Domain/Post/Post.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/PostConstraint.php');

        $this->assertFileExists($response->outputFolder . '/Domain/Post/Request/IndexPostRequest.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Request/CreatePostRequest.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Request/ReadPostRequest.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Request/UpdatePostRequest.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Request/DeletePostRequest.php');

        $this->assertFileExists($response->outputFolder . '/Domain/Post/Response/IndexPostResponse.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Response/CreatePostResponse.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Response/ReadPostResponse.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Response/UpdatePostResponse.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/Response/DeletePostResponse.php');

        $this->assertFileExists($response->outputFolder . '/Domain/Post/UseCase/IndexPostUseCase.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/UseCase/CreatePostUseCase.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/UseCase/ReadPostUseCase.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/UseCase/UpdatePostUseCase.php');
        $this->assertFileExists($response->outputFolder . '/Domain/Post/UseCase/DeletePostUseCase.php');

        $this->assertDirectoryExists($response->outputFolder . '/Domain/Comment');

        parent::deleteFolder(\dirname($response->outputFolder), false);
    }
}
