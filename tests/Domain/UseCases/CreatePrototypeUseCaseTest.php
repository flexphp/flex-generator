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

final class CreatePrototypeUseCaseTest extends TestCase
{
    public function testItWithSymfony(): void
    {
        $name = 'project';
        $sheets = [
            'Posts' => \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
            'Comments' => \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
            'Users' => \sprintf('%1$s/../../Mocks/yaml/users.yaml', __DIR__),
        ];

        $request = new CreatePrototypeRequest($name, $sheets, $this->getOutputFolder());

        $useCase = new CreatePrototypeUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreatePrototypeResponse::class, $response);

        $this->assertFileExists($response->outputDir . '/composer.json');
        $this->assertFileExists($response->outputDir . '/.env.example');
        $this->assertFileExists($response->outputDir . '/.env.test.example');
        $this->assertFileExists($response->outputDir . '/.gitignore');
        $this->assertFileExists($response->outputDir . '/CHANGELOG.md');
        $this->assertFileExists($response->outputDir . '/LICENSE.md');
        $this->assertFileExists($response->outputDir . '/README.md');
        $this->assertFileExists($response->outputDir . '/phpunit.xml.dist');

        $this->assertDirectoryExists($response->outputDir . '/src');
        $this->assertFileExists($response->outputDir . '/src/Kernel.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Migrations');

        $this->assertDirectoryExists($response->outputDir . '/src/Form');
        $this->assertFileExists($response->outputDir . '/src/Form/Type/Select2Type.php');
        $this->assertFileExists($response->outputDir . '/src/Form/Type/DatepickerType.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Listener');
        $this->assertFileExists($response->outputDir . '/src/Listener/CsrfListener.php');
        $this->assertFileExists($response->outputDir . '/src/Listener/LocaleListener.php');
        $this->assertFileExists($response->outputDir . '/src/Listener/ExceptionListener.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Twig');
        $this->assertFileExists($response->outputDir . '/src/Twig/AppExtension.php');
        $this->assertFileExists($response->outputDir . '/src/Twig/AppRuntime.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Security');
        $this->assertFileExists($response->outputDir . '/src/Security/LoginFormAuthenticator.php');
        $this->assertFileExists($response->outputDir . '/src/Security/UserProvider.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Controller');
        $this->assertFileExists($response->outputDir . '/src/Controller/.gitignore');
        $this->assertFileExists($response->outputDir . '/src/Controller/DashboardController.php');
        $this->assertFileExists($response->outputDir . '/src/Controller/LocaleController.php');
        $this->assertFileExists($response->outputDir . '/src/Controller/SecurityController.php');
        $this->assertFileExists($response->outputDir . '/src/Controller/HomepageController.php');
        $this->assertFileExists($response->outputDir . '/src/Controller/PostController.php');

        $this->assertDirectoryExists($response->outputDir . '/src/Command');
        $this->assertDirectoryExists($response->outputDir . '/src/Command/Post');
        $this->assertFileExists($response->outputDir . '/src/Command/Post/CreatePostCommand.php');
        $this->assertFileExists($response->outputDir . '/src/Command/Post/ReadPostCommand.php');
        $this->assertFileExists($response->outputDir . '/src/Command/Post/UpdatePostCommand.php');
        $this->assertFileExists($response->outputDir . '/src/Command/Post/DeletePostCommand.php');

        $this->assertDirectoryExists($response->outputDir . '/templates');
        $this->assertDirectoryExists($response->outputDir . '/templates/dashboard');
        $this->assertFileExists($response->outputDir . '/templates/dashboard/index.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/post');
        $this->assertFileExists($response->outputDir . '/templates/post/index.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/post/new.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/post/show.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/post/edit.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/post/_delete_form.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/post/_ajax.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/bin');
        $this->assertFileExists($response->outputDir . '/bin/console');

        $this->assertDirectoryExists($response->outputDir . '/config');
        $this->assertFileExists($response->outputDir . '/config/bootstrap.php');
        $this->assertFileExists($response->outputDir . '/config/bundles.php');
        $this->assertFileExists($response->outputDir . '/config/services.yaml');
        $this->assertFileExists($response->outputDir . '/config/routes.yaml');
        $this->assertFileExists($response->outputDir . '/config/menu.php');
        
        $this->assertDirectoryExists($response->outputDir . '/config/packages');
        $this->assertFileExists($response->outputDir . '/config/packages/security.yaml');
        $this->assertFileExists($response->outputDir . '/config/packages/translation.yaml');
        $this->assertFileExists($response->outputDir . '/config/packages/framework.yaml');
        $this->assertFileExists($response->outputDir . '/config/packages/twig.yaml');

        $this->assertDirectoryExists($response->outputDir . '/public');
        $this->assertFileExists($response->outputDir . '/public/index.php');
        $this->assertFileExists($response->outputDir . '/public/robots.txt');
        $this->assertFileExists($response->outputDir . '/public/.htaccess');
        $this->assertFileExists($response->outputDir . '/public/favicon.ico');

        $this->assertDirectoryExists($response->outputDir . '/public/css');
        $this->assertFileExists($response->outputDir . '/public/css/main.css');

        $this->assertDirectoryExists($response->outputDir . '/public/css/bootstrap');
        $this->assertFileExists($response->outputDir . '/public/css/bootstrap/bootstrap.min.css');
        $this->assertDirectoryExists($response->outputDir . '/public/css/select2');
        $this->assertFileExists($response->outputDir . '/public/css/select2/select2.min.css');
        $this->assertFileExists($response->outputDir . '/public/css/select2/select2bs4.min.css');
        $this->assertDirectoryExists($response->outputDir . '/public/css/fontawesome');
        $this->assertFileExists($response->outputDir . '/public/css/fontawesome/all.min.css');
        $this->assertDirectoryExists($response->outputDir . '/public/css/datepicker');
        $this->assertFileExists($response->outputDir . '/public/css/datepicker/datepicker.min.css');

        $this->assertDirectoryExists($response->outputDir . '/public/css/webfonts/');
        $this->assertFileExists($response->outputDir . '/public/css/webfonts/fonts.css');
        $this->assertFileExists($response->outputDir . '/public/css/webfonts/fa-solid-900.woff2');
        $this->assertFileExists($response->outputDir . '/public/css/webfonts/CircularStd-Book.woff');
        $this->assertFileExists($response->outputDir . '/public/css/webfonts/CircularStd-Medium.woff');

        $this->assertDirectoryExists($response->outputDir . '/public/js');
        $this->assertFileExists($response->outputDir . '/public/js/main.js');
        $this->assertFileExists($response->outputDir . '/public/js/comments.js');

        $this->assertDirectoryExists($response->outputDir . '/public/js/jquery');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/jquery.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/jquery.slimscroll.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/jquery.select2.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/jquery.infinite.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/jquery.datepicker.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/chart.bundle.min.js');

        $this->assertDirectoryExists($response->outputDir . '/public/js/bootstrap');
        $this->assertFileExists($response->outputDir . '/public/js/bootstrap/bootstrap.min.js');

        $this->assertDirectoryExists($response->outputDir . '/public/js/jquery/locales');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/locales/bootstrap-datepicker.en-US.min.js');
        $this->assertFileExists($response->outputDir . '/public/js/jquery/locales/select2.en-US.min.js');

        $this->assertDirectoryExists($response->outputDir . '/templates');
        $this->assertFileExists($response->outputDir . '/templates/base.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/default');
        $this->assertFileExists($response->outputDir . '/templates/default/_flash.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/default/_infinite.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/default/_back_button.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/default/_filter_button.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/default/homepage.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/form');
        $this->assertFileExists($response->outputDir . '/templates/form/layout.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/form/_delete_confirmation.html.twig');
        $this->assertFileExists($response->outputDir . '/templates/form/fields.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/templates/security');
        $this->assertFileExists($response->outputDir . '/templates/security/login.html.twig');

        $this->assertDirectoryExists($response->outputDir . '/translations');
        $this->assertFileExists($response->outputDir . '/translations/.gitignore');
        $this->assertFileExists($response->outputDir . '/translations/dashboard.en.php');
        $this->assertFileExists($response->outputDir . '/translations/dashboard.es.php');
        $this->assertFileExists($response->outputDir . '/translations/login.en.php');
        $this->assertFileExists($response->outputDir . '/translations/login.es.php');
        $this->assertFileExists($response->outputDir . '/translations/messages.en.php');
        $this->assertFileExists($response->outputDir . '/translations/messages.es.php');
        $this->assertFileExists($response->outputDir . '/translations/error.en.php');
        $this->assertFileExists($response->outputDir . '/translations/error.es.php');
        $this->assertFileExists($response->outputDir . '/translations/post.en.php');
        $this->assertFileExists($response->outputDir . '/translations/comment.en.php');

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
        $this->assertFileExists($response->outputDir . '/tests/TestCase.php');
        $this->assertFileExists($response->outputDir . '/tests/WebTestCase.php');

        $this->assertDirectoryExists($response->outputDir . '/domain');
        $this->assertDirectoryExists($response->outputDir . '/domain/Database');
        $this->assertFileExists($response->outputDir . '/domain/Database/1 - create.sql');
        
        $this->assertDirectoryExists($response->outputDir . '/domain/Helper');
        $this->assertFileExists($response->outputDir . '/domain/Helper/DateTimeTrait.php');
        $this->assertFileExists($response->outputDir . '/domain/Helper/DbalCriteriaHelper.php');
        
        $this->assertDirectoryExists($response->outputDir . '/domain/Tests');
        $this->assertFileExists($response->outputDir . '/domain/Tests/TestCase.php');
        $this->assertFileExists($response->outputDir . '/domain/Tests/AbstractGateway.php');

        $this->assertDirectoryExists($response->outputDir . '/domain/User');
        $this->assertFileExists($response->outputDir . '/domain/User/UserRbac.php');

        $this->assertDirectoryExists($response->outputDir . '/domain/Post');
        $this->assertDirectoryExists($response->outputDir . '/domain/Post/Request');
        $this->assertDirectoryExists($response->outputDir . '/domain/Post/Response');
        $this->assertDirectoryExists($response->outputDir . '/domain/Post/UseCase');
        $this->assertDirectoryExists($response->outputDir . '/domain/Post/Gateway');

        $this->assertFileExists($response->outputDir . '/domain/Post/Post.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/PostFactory.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/PostGateway.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/PostRepository.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/PostConstraint.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/PostFormType.php');

        $this->assertFileExists($response->outputDir . '/domain/Post/Request/IndexPostRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Request/CreatePostRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Request/ReadPostRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Request/UpdatePostRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Request/DeletePostRequest.php');

        $this->assertFileExists($response->outputDir . '/domain/Post/Response/IndexPostResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Response/CreatePostResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Response/ReadPostResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Response/UpdatePostResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/Response/DeletePostResponse.php');

        $this->assertFileExists($response->outputDir . '/domain/Post/UseCase/IndexPostUseCase.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/UseCase/CreatePostUseCase.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/UseCase/ReadPostUseCase.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/UseCase/UpdatePostUseCase.php');
        $this->assertFileExists($response->outputDir . '/domain/Post/UseCase/DeletePostUseCase.php');

        $this->assertFileExists($response->outputDir . '/domain/Post/Gateway/MySQLPostGateway.php');
        
        $this->assertDirectoryExists($response->outputDir . '/domain/Comment');
        $this->assertFileExists($response->outputDir . '/domain/Comment/Request/FindCommentPostRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/Comment/Response/FindCommentPostResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/Comment/UseCase/FindCommentPostUseCase.php');
        
        $this->assertDirectoryExists($response->outputDir . '/domain/User');
        $this->assertFileExists($response->outputDir . '/domain/User/Request/LoginUserRequest.php');
        $this->assertFileExists($response->outputDir . '/domain/User/Response/LoginUserResponse.php');
        $this->assertFileExists($response->outputDir . '/domain/User/UseCase/LoginUserUseCase.php');
        $this->assertFileNotExists($response->outputDir . '/domain/User/Command/LoginUserCommand.php');

        parent::deleteFolder(\dirname($response->outputDir));
    }
}
