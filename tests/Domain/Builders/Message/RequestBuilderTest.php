<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Message;

use FlexPHP\Generator\Domain\Builders\Message\RequestBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class RequestBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class ActionFuzRequest implements RequestInterface
{
    public \$lower;
    public \$upper;
    public \$pascalCase;
    public \$camelCase;
    public \$snakeCase;

    public function __construct(array \$data)
    {
        \$this->lower = \$data['lower'] ?? null;
        \$this->upper = \$data['upper'] ?? null;
        \$this->pascalCase = \$data['pascalCase'] ?? null;
        \$this->camelCase = \$data['camelCase'] ?? null;
        \$this->snakeCase = \$data['snakeCase'] ?? null;
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class CreateFuzRequest implements RequestInterface
{
    public \$lower;
    public \$upper;
    public \$pascalCase;
    public \$camelCase;
    public \$snakeCase;

    public function __construct(array \$data)
    {
        \$this->lower = \$data['lower'] ?? null;
        \$this->upper = \$data['upper'] ?? null;
        \$this->pascalCase = \$data['pascalCase'] ?? null;
        \$this->camelCase = \$data['camelCase'] ?? null;
        \$this->snakeCase = \$data['snakeCase'] ?? null;
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'read');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class ReadFuzRequest implements RequestInterface
{
    public \$lower;

    public function __construct(string \$lower)
    {
        \$this->lower = \$lower;
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'update');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateFuzRequest implements RequestInterface
{
    public \$lower;
    public \$upper;
    public \$pascalCase;
    public \$camelCase;
    public \$snakeCase;

    public function __construct(array \$data)
    {
        \$this->lower = \$data['lower'] ?? null;
        \$this->upper = \$data['upper'] ?? null;
        \$this->pascalCase = \$data['pascalCase'] ?? null;
        \$this->camelCase = \$data['camelCase'] ?? null;
        \$this->snakeCase = \$data['snakeCase'] ?? null;
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'delete');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class DeleteFuzRequest implements RequestInterface
{
    public \$lower;

    public function __construct(string \$lower)
    {
        \$this->lower = \$lower;
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'login');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class LoginFuzRequest implements RequestInterface
{
    public \$email;

    public function __construct(string \$email)
    {
        \$this->email = \$email;
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
        $render = new RequestBuilder(new Schema($entity, 'bar', []), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\Request;

use FlexPHP\Messages\RequestInterface;

final class Action{$expected}Request implements RequestInterface
{
    public function __construct(array \$data)
    {
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
        $render = new RequestBuilder(new Schema('Fuz', 'bar', []), $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class {$expected}FuzRequest implements RequestInterface
{
    public function __construct(array \$data)
    {
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
        $render = new RequestBuilder(new Schema('Fuz', 'bar', [
            new SchemaAttribute($name, 'integer'),
        ]), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class ActionFuzRequest implements RequestInterface
{
    public \${$expected};

    public function __construct(array \$data)
    {
        \$this->{$expected} = \$data['{$expected}'] ?? null;
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
        ];
    }
}
