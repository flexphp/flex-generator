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
        $action = 'action';
        $entity = 'Test';
        $properties = [
            [
                Keyword::NAME => 'foo',
                Keyword::DATATYPE => 'integer',
            ],
            [
                Keyword::NAME => 'bar',
                Keyword::DATATYPE => 'varchar',
            ],
        ];

        $render = new UseCaseBuilder($entity, $action, $properties);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\UseCase;

use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Response\ActionTestResponse;
use FlexPHP\UseCases\UseCase;

final class ActionTestUseCase extends UseCase
{
    private \$foo;
    private \$bar;

    /**
     * @param ActionTestRequest \$request
     *
     * @return ActionTestResponse
     */
    public function execute(\$request)
    {
        \$this->foo = \$request->foo;
        \$this->bar = \$request->bar;

        return new ActionTestResponse();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffEntityName(string $entity, string $expected): void
    {
        $action = 'action';

        $render = new UseCaseBuilder($entity, $action, []);

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
    public function testItOkWithDiffActionName(string $action, string $expected): void
    {
        $entity = 'Test';

        $render = new UseCaseBuilder($entity, $action, []);

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
    public function testItOkWithDiffPropertyName(string $name, string $expected): void
    {
        $action = 'action';
        $entity = 'Test';

        $render = new UseCaseBuilder($entity, $action, [
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
