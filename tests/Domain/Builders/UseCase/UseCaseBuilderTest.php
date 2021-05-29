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
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class UseCaseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Response\ActionTestResponse;

final class ActionTestUseCase
{
    private \$lower;
    private \$upper;
    private \$pascalCase;
    private \$camelCase;
    private \$snakeCase;

    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(ActionTestRequest \$request): ActionTestResponse
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
        $render = new UseCaseBuilder($this->getSchema(), 'index');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\IndexTestRequest;
use Domain\Test\Response\IndexTestResponse;

final class IndexTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(IndexTestRequest \$request): IndexTestResponse
    {
        \$tests = \$this->testRepository->findBy(\$request);

        return new IndexTestResponse(\$tests);
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\CreateTestRequest;
use Domain\Test\Response\CreateTestResponse;

final class CreateTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(CreateTestRequest \$request): CreateTestResponse
    {
        return new CreateTestResponse(\$this->testRepository->add(\$request));
    }
}

T
, $render->build());
    }

    public function testItRenderCreateWithCheckForeignKey(): void
    {
        $render = new UseCaseBuilder($this->getSchemaFkWithFilterAndFchars(), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Check\CheckRepository;
use Domain\Check\Request\ReadCheckRequest;
use Domain\Test\TestRepository;
use Domain\Test\Request\CreateTestRequest;
use Domain\Test\Response\CreateTestResponse;
use Exception;

final class CreateTestUseCase
{
    private TestRepository \$testRepository;

    private CheckRepository \$checkRepository;

    public function __construct(
        TestRepository \$testRepository,
        CheckRepository \$checkRepository
    ) {
        \$this->testRepository = \$testRepository;
        \$this->checkRepository = \$checkRepository;
    }

    public function execute(CreateTestRequest \$request): CreateTestResponse
    {
        if (\$request->fkcheck
            && !\$this->checkRepository->getById(new ReadCheckRequest(\$request->fkcheck))->id()
        ) {
            throw new Exception('Check not found [%s]', \$request->fkcheck);
        }

        return new CreateTestResponse(\$this->testRepository->add(\$request));
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'read');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\ReadTestRequest;
use Domain\Test\Response\ReadTestResponse;

final class ReadTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(ReadTestRequest \$request): ReadTestResponse
    {
        \$test = \$this->testRepository->getById(\$request);

        return new ReadTestResponse(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'update');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\UpdateTestRequest;
use Domain\Test\Response\UpdateTestResponse;

final class UpdateTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(UpdateTestRequest \$request): UpdateTestResponse
    {
        return new UpdateTestResponse(\$this->testRepository->change(\$request));
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'delete');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\DeleteTestRequest;
use Domain\Test\Response\DeleteTestResponse;

final class DeleteTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(DeleteTestRequest \$request): DeleteTestResponse
    {
        return new DeleteTestResponse(\$this->testRepository->remove(\$request));
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new UseCaseBuilder($this->getSchema(), 'login');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\LoginTestRequest;
use Domain\Test\Response\LoginTestResponse;

final class LoginTestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(LoginTestRequest \$request): LoginTestResponse
    {
        \$test = \$this->testRepository->getByLogin(\$request);

        return new LoginTestResponse(\$test);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOkWithDiffEntityName(string $entity, string $expected, string $expectedItem): void
    {
        $render = new UseCaseBuilder(new Schema($entity, 'bar', []), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$expected}\\UseCase;

use Domain\\{$expected}\\{$expected}Repository;
use Domain\\{$expected}\Request\Action{$expected}Request;
use Domain\\{$expected}\Response\Action{$expected}Response;

final class Action{$expected}UseCase
{
    private {$expected}Repository \${$expectedItem}Repository;

    public function __construct({$expected}Repository \${$expectedItem}Repository)
    {
        \$this->{$expectedItem}Repository = \${$expectedItem}Repository;
    }

    public function execute(Action{$expected}Request \$request): Action{$expected}Response
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
        $render = new UseCaseBuilder(new Schema('Test', 'bar', []), $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\\{$expected}TestRequest;
use Domain\Test\Response\\{$expected}TestResponse;

final class {$expected}TestUseCase
{
    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute({$expected}TestRequest \$request): {$expected}TestResponse
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
        $render = new UseCaseBuilder(new Schema('Test', 'bar', [
            new SchemaAttribute($name, 'integer'),
        ]), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\UseCase;

use Domain\Test\TestRepository;
use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Response\ActionTestResponse;

final class ActionTestUseCase
{
    private \${$expected};

    private TestRepository \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function execute(ActionTestRequest \$request): ActionTestResponse
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
            ['userpassword', 'Userpassword', 'userpassword'],
            ['USERPASSWORD', 'Userpassword', 'userpassword'],
            ['UserPassword', 'UserPassword', 'userPassword'],
            ['userPassword', 'UserPassword', 'userPassword'],
            ['user_password', 'UserPassword', 'userPassword'],
            ['Posts', 'Post', 'post'],
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
        ];
    }
}
