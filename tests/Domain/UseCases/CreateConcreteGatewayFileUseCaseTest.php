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

use FlexPHP\Generator\Domain\Messages\Requests\CreateConcreteGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConcreteGatewayFileResponse;
use FlexPHP\Generator\Domain\UseCases\CreateConcreteGatewayFileUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use InvalidArgumentException;

final class CreateConcreteGatewayFileUseCaseTest extends TestCase
{
    /**
     * @dataProvider getEntityFile()
     */
    public function testItNotValidConcreteThrowException(string $schemafile): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('mysql is not valid');

        $schema = Schema::fromFile($schemafile);

        $request = new CreateConcreteGatewayFileRequest($schema->name(), 'mysql', ['create'], $schema);

        $useCase = new CreateConcreteGatewayFileUseCase();
        $useCase->execute($request);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItMySQLOk(string $schemafile, string $expectedFile): void
    {
        $concrete = 'MySQL';
        $schema = Schema::fromFile($schemafile);

        $request = new CreateConcreteGatewayFileRequest($schema->name(), 'MySQL', [
            'create',
        ], $schema);

        $useCase = new CreateConcreteGatewayFileUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateConcreteGatewayFileResponse::class, $response);
        $file = $response->file;

        $filename = \explode('/', $file);
        $this->assertEquals($concrete . $expectedFile, \array_pop($filename));
        $this->assertFileExists($file);

        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            [\sprintf('%1$s/../../Mocks/yaml/posts.yaml', __DIR__), 'PostGateway.php'],
            [\sprintf('%1$s/../../Mocks/yaml/comments.yaml', __DIR__), 'CommentGateway.php'],
        ];
    }
}
