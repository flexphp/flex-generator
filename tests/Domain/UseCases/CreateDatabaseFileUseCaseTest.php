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

use FlexPHP\Generator\Domain\Messages\Requests\CreateDatabaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateDatabaseFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateDatabaseFileUseCase;
use FlexPHP\Generator\Tests\TestCase;

final class CreateDatabaseFileUseCaseTest extends TestCase
{
    public function testItMySQLOk(): void
    {
        $platform = 'MySQL';
        $dbname = 'test';
        $username = 'foo';
        $password = '';
        $yamls = [
            \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
            \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
        ];

        $request = new CreateDatabaseFileRequest($platform, $dbname, $username, $password, $yamls);

        $useCase = new CreateDatabaseFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateDatabaseFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals('database.sql', \array_pop($filename));
        $this->assertFileExists($file);

        $content = file_get_contents($file);

        $this->assertContains('USE ', $content);
        $this->assertContains('GRANT ALL', $content);

        \unlink($file);
    }

    public function testItSQLSrvOk(): void
    {
        $platform = 'SQLSrv';
        $dbname = 'test';
        $username = 'foo';
        $password = '';
        $yamls = [
            \sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__),
            \sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__),
        ];

        $request = new CreateDatabaseFileRequest($platform, $dbname, $username, $password, $yamls);

        $useCase = new CreateDatabaseFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateDatabaseFileResponse::class, $response);
        $file = $response->file;
        $filename = \explode('/', $file);
        $this->assertEquals('database.sql', \array_pop($filename));
        $this->assertFileExists($file);

        \unlink($file);
    }
}
