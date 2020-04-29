<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\UseCase\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Constants\Keyword;

final class UseCaseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new UseCaseBuilder('Test', 'action', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Response\ActionTestResponse;
use FlexPHP\UseCases\UseCase;

final class ActionTestUseCase extends UseCase
{
    private \$lower;
    private \$upper;
    private \$pascalCase;
    private \$camelCase;
    private \$snakeCase;

    /**
     * @param ActionTestRequest \$request
     *
     * @return ActionTestResponse
     */
    public function execute(\$request)
    {
        \$this->lower = \$request->lower;
        \$this->upper = \$request->upper;
        \$this->pascalCase = \$request->pascalCase;
        \$this->camelCase = \$request->camelCase;
        \$this->snakeCase = \$request->snakeCase;

        return new ActionTestResponse();
    }
}

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new UseCaseBuilder('Test', 'index', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\Response\IndexTestResponse;
use FlexPHP\UseCases\UseCase;

final class IndexTestUseCase extends UseCase
{
    /**
     * @param IndexTestRequest \$request
     *
     * @return IndexTestResponse
     */
    public function execute(\$request)
    {
        \$tests = \$this->getRepository()->findBy(\$request);

        return new IndexTestResponse(\$tests);
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new UseCaseBuilder('Test', 'create', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\CreateTestRequest;
use Domain\Test\Response\CreateTestResponse;
use FlexPHP\UseCases\UseCase;

final class CreateTestUseCase extends UseCase
{
    /**
     * @param CreateTestRequest \$request
     *
     * @return CreateTestResponse
     */
    public function execute(\$request)
    {
        \$this->getRepository()->add(\$request);

        return new CreateTestResponse(201, 'success', 'Test was created!');
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new UseCaseBuilder('Test', 'read', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\ReadTestRequest;
use Domain\Test\Response\ReadTestResponse;
use FlexPHP\UseCases\UseCase;

final class ReadTestUseCase extends UseCase
{
    /**
     * @param ReadTestRequest \$request
     *
     * @return ReadTestResponse
     */
    public function execute(\$request)
    {
        \$test = \$this->getRepository()->getById(\$request);

        return new ReadTestResponse(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new UseCaseBuilder('Test', 'update', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\UpdateTestRequest;
use Domain\Test\Response\UpdateTestResponse;
use FlexPHP\UseCases\UseCase;

final class UpdateTestUseCase extends UseCase
{
    /**
     * @param UpdateTestRequest \$request
     *
     * @return UpdateTestResponse
     */
    public function execute(\$request)
    {
        \$this->getRepository()->change(\$request);

        return new UpdateTestResponse(200, 'success', 'Test was updated!');
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new UseCaseBuilder('Test', 'delete', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\DeleteTestRequest;
use Domain\Test\Response\DeleteTestResponse;
use FlexPHP\UseCases\UseCase;

final class DeleteTestUseCase extends UseCase
{
    /**
     * @param DeleteTestRequest \$request
     *
     * @return DeleteTestResponse
     */
    public function execute(\$request)
    {
        \$this->getRepository()->remove(\$request);

        return new DeleteTestResponse(200, 'success', 'Test was deleted!');
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new UseCaseBuilder('Test', 'login', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\LoginTestRequest;
use Domain\Test\Response\LoginTestResponse;
use FlexPHP\UseCases\UseCase;

final class LoginTestUseCase extends UseCase
{
    /**
     * @param LoginTestRequest \$request
     *
     * @return LoginTestResponse
     */
    public function execute(\$request)
    {
        \$test = \$this->getRepository()->getByLogin(\$request);

        return new LoginTestResponse(\$test);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOkWithDiffEntityName(string $entity, string $expected): void
    {
        $render = new UseCaseBuilder($entity, 'action', []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\\UseCase;

use Domain\\{$expected}\Request\Action{$expected}Request;
use Domain\\{$expected}\Response\Action{$expected}Response;
use FlexPHP\UseCases\UseCase;

final class Action{$expected}UseCase extends UseCase
{
    /**
     * @param Action{$expected}Request \$request
     *
     * @return Action{$expected}Response
     */
    public function execute(\$request)
    {
        return new Action{$expected}Response();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getActionName
     */
    public function testItRenderOkWithDiffActionName(string $action, string $expected): void
    {
        $render = new UseCaseBuilder('test', $action, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\\{$expected}TestRequest;
use Domain\Test\Response\\{$expected}TestResponse;
use FlexPHP\UseCases\UseCase;

final class {$expected}TestUseCase extends UseCase
{
    /**
     * @param {$expected}TestRequest \$request
     *
     * @return {$expected}TestResponse
     */
    public function execute(\$request)
    {
        return new {$expected}TestResponse();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getPropertyName
     */
    public function testItRenderOkWithDiffPropertyName(string $name, string $expected): void
    {
        $render = new UseCaseBuilder('Test', 'action', [
            [
                Keyword::NAME => $name,
                Keyword::DATATYPE => 'integer',
            ],
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Response\ActionTestResponse;
use FlexPHP\UseCases\UseCase;

final class ActionTestUseCase extends UseCase
{
    private \${$expected};

    /**
     * @param ActionTestRequest \$request
     *
     * @return ActionTestResponse
     */
    public function execute(\$request)
    {
        \$this->{$expected} = \$request->{$expected};

        return new ActionTestResponse();
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }

    public function getActionName(): array
    {
        return [
            ['custom_action', 'CustomAction'],
            ['custom action', 'CustomAction'],
            ['Custom Action', 'CustomAction'],
            ['cUSTOM aCtion', 'CustomAction'],
            ['customAction', 'CustomAction'],
            ['CustomAction', 'CustomAction'],
            ['custom-action', 'CustomAction'],
        ];
    }

    public function getPropertyName(): array
    {
        return [
            ['fooname', 'fooname'],
            ['FOONAME', 'fooname'],
            ['FooName', 'fooName'],
            ['fooName', 'fooName'],
            ['foo_name', 'fooName'],
            ['foo-name', 'fooName'],
        ];
    }
}
