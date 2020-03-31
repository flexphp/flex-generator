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
        $action = 'action';
        $entity = 'Fuz';

        $render = new ResponseBuilder($entity, $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class ActionFuzResponse implements ResponseInterface
{
    public function __construct(array \$data)
    {
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
        $action = 'action';

        $render = new ResponseBuilder($entity, $action, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\Response;

use FlexPHP\Messages\ResponseInterface;

final class Action{$expected}Response implements ResponseInterface
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
        $entity = 'Fuz';

        $render = new ResponseBuilder($entity, $action, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class {$expected}FuzResponse implements ResponseInterface
{
    public function __construct(array \$data)
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
}
