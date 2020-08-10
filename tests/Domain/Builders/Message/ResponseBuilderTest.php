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

use FlexPHP\Generator\Domain\Builders\Message\ResponseBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class ResponseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class ActionFuzResponse implements ResponseInterface
{
    public function __construct(array \$data = [])
    {
    }
}

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'index');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexFuzResponse implements ResponseInterface
{
    public \$fuzes;

    public function __construct(array \$fuzes)
    {
        \$this->fuzes = \$fuzes;
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'create');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use Domain\Fuz\Fuz;
use FlexPHP\Messages\ResponseInterface;

final class CreateFuzResponse implements ResponseInterface
{
    public \$fuz;

    public function __construct(Fuz \$fuz)
    {
        \$this->fuz = \$fuz;
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'read');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use Domain\Fuz\Fuz;
use FlexPHP\Messages\ResponseInterface;

final class ReadFuzResponse implements ResponseInterface
{
    public \$fuz;

    public function __construct(Fuz \$fuz)
    {
        \$this->fuz = \$fuz;
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'update');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use Domain\Fuz\Fuz;
use FlexPHP\Messages\ResponseInterface;

final class UpdateFuzResponse implements ResponseInterface
{
    public \$fuz;

    public function __construct(Fuz \$fuz)
    {
        \$this->fuz = \$fuz;
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'delete');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class DeleteFuzResponse implements ResponseInterface
{
    public \$code;
    public \$status;
    public \$message;

    public function __construct(int \$code, string \$status, string \$message)
    {
        \$this->code = \$code;
        \$this->status = \$status;
        \$this->message = \$message;
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new ResponseBuilder($this->getSchema('Fuz'), 'login');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use Domain\Fuz\Fuz;
use FlexPHP\Messages\ResponseInterface;

final class LoginFuzResponse implements ResponseInterface
{
    public \$fuz;

    public function __construct(Fuz \$fuz)
    {
        \$this->fuz = \$fuz;
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
        $render = new ResponseBuilder($this->getSchema($entity), 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\Response;

use FlexPHP\Messages\ResponseInterface;

final class Action{$expected}Response implements ResponseInterface
{
    public function __construct(array \$data = [])
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
        $render = new ResponseBuilder($this->getSchema('Fuz'), $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class {$expected}FuzResponse implements ResponseInterface
{
    public function __construct(array \$data = [])
    {
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
}
