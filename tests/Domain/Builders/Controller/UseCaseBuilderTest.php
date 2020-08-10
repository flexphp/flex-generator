<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class UseCaseBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOk(string $entity, string $expected): void
    {
        $render = new UseCaseBuilder(new Schema($entity, 'bar', []), 'action');

        $this->assertEquals(<<<T
        \$useCase = new Action{$expected}UseCase();
        \$response = \$useCase->execute(\$request);
T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), 'index');

        $this->assertEquals(<<<T
        \$useCase = new IndexTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);
T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), 'create');

        $this->assertEquals(<<<T
        \$useCase = new CreateTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$useCase->execute(\$request);
T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), 'read');

        $this->assertEquals(<<<T
        \$useCase = new ReadTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->id()) {
            throw \$this->createNotFoundException();
        }
T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderReadDiffEntityNameOk(string $name, string $expected, string $item): void
    {
        $render = new UseCaseBuilder(new Schema($name, 'bar', []), 'read');

        $this->assertEquals(<<<T
        \$useCase = new Read{$expected}UseCase(new {$expected}Repository(new MySQL{$expected}Gateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->{$item}->id()) {
            throw \$this->createNotFoundException();
        }
T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), 'update');

        $this->assertEquals(<<<T
        \$useCase = new UpdateTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$useCase->execute(\$request);
T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), 'delete');

        $this->assertEquals(<<<T
        \$useCase = new DeleteTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);
T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            // entity, function, item
            ['userpassword', 'Userpassword', 'userpassword'],
            ['USERPASSWORD', 'Userpassword', 'userpassword'],
            ['UserPassword', 'UserPassword', 'userPassword'],
            ['userPassword', 'UserPassword', 'userPassword'],
            ['user_password', 'UserPassword', 'userPassword'],
            ['Posts', 'Post', 'post'],
        ];
    }
}
