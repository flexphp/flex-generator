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

        $this->assertFileExists($response->outputDir . '/composer.json');
        $this->assertFileExists($response->outputDir . '/.env.example');

        $this->assertDirectoryExists($response->outputDir . '/src');
        $this->assertFileExists($response->outputDir . '/src/Kernel.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Migrations');

        $this->assertDirectoryExists($response->outputDir . '/src/Controller');
        $this->assertFileExists($response->outputDir . '/src/Controller/PostController.php');

        // $this->assertDirectoryExists($response->outputDir . '/src/Command');
        // $this->assertFileExists($response->outputDir . '/src/Command/CreatePostCommand.php');
        // $this->assertFileExists($response->outputDir . '/src/Command/ReadPostCommand.php');
        // $this->assertFileExists($response->outputDir . '/src/Command/UpdatePostCommand.php');
        // $this->assertFileExists($response->outputDir . '/src/Command/DeletePostCommand.php');

        $this->assertDirectoryExists($response->outputDir . '/bin');
        $this->assertFileExists($response->outputDir . '/bin/console');

        $this->assertDirectoryExists($response->outputDir . '/config');
        $this->assertFileExists($response->outputDir . '/config/bootstrap.php');
        $this->assertFileExists($response->outputDir . '/config/bundles.php');
        $this->assertFileExists($response->outputDir . '/config/services.yaml');

        $this->assertDirectoryExists($response->outputDir . '/public');
        $this->assertFileExists($response->outputDir . '/public/index.php');
        $this->assertFileExists($response->outputDir . '/public/robots.txt');
        $this->assertFileExists($response->outputDir . '/public/.htaccess');
        $this->assertFileExists($response->outputDir . '/public/favicon.ico');

        $this->assertDirectoryExists($response->outputDir . '/templates');
        $this->assertFileExists($response->outputDir . '/templates/base.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/default');
        $this->assertFileExists($response->outputDir . '/templates/default/_flash.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/default/homepage.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/form');
        $this->assertFileExists($response->outputDir . '/templates/form/layout.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/security');
        $this->assertFileExists($response->outputDir . '/templates/security/login.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/errors');
        $this->assertFileExists($response->outputDir . '/templates/errors/error.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/errors/error403.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/errors/error404.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/errors/error500.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/var');
        $this->assertDirectoryExists($response->outputDir . '/var/cache');
        $this->assertDirectoryExists($response->outputDir . '/var/log');
        $this->assertDirectoryExists($response->outputDir . '/var/sessions');

        $this->assertDirectoryExists($response->outputDir . '/tests');

        $this->assertDirectoryExists($response->outputDir . '/Domain');
        $this->assertDirectoryExists($response->outputDir . '/Domain/Post');
        $this->assertDirectoryExists($response->outputDir . '/Domain/Post/Request');
        $this->assertDirectoryExists($response->outputDir . '/Domain/Post/Response');
        $this->assertDirectoryExists($response->outputDir . '/Domain/Post/UseCase');

        $this->assertFileExists($response->outputDir . '/Domain/Post/Post.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/PostConstraint.php');

        $this->assertFileExists($response->outputDir . '/Domain/Post/Request/IndexPostRequest.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Request/CreatePostRequest.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Request/ReadPostRequest.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Request/UpdatePostRequest.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Request/DeletePostRequest.php');

        $this->assertFileExists($response->outputDir . '/Domain/Post/Response/IndexPostResponse.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Response/CreatePostResponse.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Response/ReadPostResponse.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Response/UpdatePostResponse.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/Response/DeletePostResponse.php');

        $this->assertFileExists($response->outputDir . '/Domain/Post/UseCase/IndexPostUseCase.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/UseCase/CreatePostUseCase.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/UseCase/ReadPostUseCase.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/UseCase/UpdatePostUseCase.php');
        $this->assertFileExists($response->outputDir . '/Domain/Post/UseCase/DeletePostUseCase.php');

        $this->assertDirectoryExists($response->outputDir . '/Domain/Comment');

        parent::deleteFolder(\dirname($response->outputDir), false);
    }
}
