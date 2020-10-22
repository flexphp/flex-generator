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

    public function testItRenderIndexOk(): void
    {
        $render = new RequestBuilder($this->getSchema('Fuz'), 'index');

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use Domain\Helper\DateTimeTrait;
use FlexPHP\Messages\RequestInterface;

final class IndexFuzRequest implements RequestInterface
{
    use DateTimeTrait;

    public $lower;
    public $upper;
    public $pascalCase;
    public $camelCase;
    public $snakeCase;
    public $page;
    public $offset;

    public function __construct(array $data, int $page, ?string $timezone = null)
    {
        $this->lower = $data['lower'] ?? null;
        $this->upper = $data['upper'] ?? null;
        $this->pascalCase = $data['pascalCase'] ?? null;
        $this->camelCase = $data['camelCase'] ?? null;
        $this->snakeCase = $data['snakeCase'] ?? null;
        $this->page = $page;
        $this->offset = $this->getOffset($this->getTimezone($timezone));
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

    public function __construct(string \$lower, array \$data)
    {
        \$this->lower = \$lower;
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

    public function testItRenderReadWithIntPkOk(): void
    {
        $render = new RequestBuilder($this->getSchemaFkRelation('Fuz'), 'read');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class ReadFuzRequest implements RequestInterface
{
    public \$pk;

    public function __construct(int \$pk)
    {
        \$this->pk = \$pk;
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteWithIntPkOk(): void
    {
        $render = new RequestBuilder($this->getSchemaFkRelation('Fuz'), 'delete');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class DeleteFuzRequest implements RequestInterface
{
    public \$pk;

    public function __construct(int \$pk)
    {
        \$this->pk = \$pk;
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

    public function testItIndexAiAndBlameAt(): void
    {
        $render = new RequestBuilder($this->getSchemaAiAndBlameAt('Bar'), 'index');

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Bar\Request;

use Domain\Helper\DateTimeTrait;
use FlexPHP\Messages\RequestInterface;

final class IndexBarRequest implements RequestInterface
{
    use DateTimeTrait;

    public $key;
    public $value;
    public $created = [];
    public $updated;
    public $page;
    public $offset;

    public function __construct(array $data, int $page, ?string $timezone = null)
    {
        $this->key = $data['key'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->created[] = $data['created_START'] ?? null;
        $this->created[] = $data['created_END'] ?? null;
        $this->updated = $data['updated'] ?? null;
        $this->page = $page;
        $this->offset = $this->getOffset($this->getTimezone($timezone));
    }
}

T
, $render->build());
    }

    public function testItCreateAiAndBlameAt(): void
    {
        $render = new RequestBuilder($this->getSchemaAiAndBlameAt('Bar'), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Bar\Request;

use FlexPHP\Messages\RequestInterface;

final class CreateBarRequest implements RequestInterface
{
    public \$value;

    public function __construct(array \$data)
    {
        \$this->value = \$data['value'] ?? null;
    }
}

T
, $render->build());
    }

    public function testItUpdateAiAndBlameAt(): void
    {
        $render = new RequestBuilder($this->getSchemaAiAndBlameAt('Bar'), 'update');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Bar\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateBarRequest implements RequestInterface
{
    public \$key;
    public \$value;

    public function __construct(int \$key, array \$data)
    {
        \$this->key = \$key;
        \$this->value = \$data['value'] ?? null;
    }
}

T
, $render->build());
    }

    public function testItCreateBlameBy(): void
    {
        $render = new RequestBuilder($this->getSchemaStringAndBlameBy('Bar'), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Bar\Request;

use FlexPHP\Messages\RequestInterface;

final class CreateBarRequest implements RequestInterface
{
    public \$code;
    public \$name;
    public \$createdBy;

    public function __construct(array \$data, int \$createdBy)
    {
        \$this->code = \$data['code'] ?? null;
        \$this->name = \$data['name'] ?? null;
        \$this->createdBy = \$createdBy;
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateBlameBy(): void
    {
        $render = new RequestBuilder($this->getSchemaStringAndBlameBy('Fuz'), 'update');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateFuzRequest implements RequestInterface
{
    public \$code;
    public \$name;
    public \$updatedBy;

    public function __construct(string \$code, array \$data, int \$updatedBy)
    {
        \$this->code = \$code;
        \$this->name = \$data['name'] ?? null;
        \$this->updatedBy = \$updatedBy;
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
