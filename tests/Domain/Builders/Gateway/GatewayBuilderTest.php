<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Gateway;

use FlexPHP\Generator\Domain\Builders\Gateway\GatewayBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class GatewayBuilderTest extends TestCase
{
    public function testItIndexOk(): void
    {
        $render = new GatewayBuilder('Test', ['index']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function find(array \$wheres, array \$orders, int \$limit): array;
}

T
, $render->build());
    }

    public function testItCreateOk(): void
    {
        $render = new GatewayBuilder('Test', ['create']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function push(Test \$test): void;
}

T
, $render->build());
    }

    public function testItReadOk(): void
    {
        $render = new GatewayBuilder('Test', ['read']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function get(Test \$test): array;
}

T
, $render->build());
    }

    public function testItUpdateOk(): void
    {
        $render = new GatewayBuilder('Test', ['update']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function shift(Test \$test): void;
}

T
, $render->build());
    }

    public function testItDeleteOk(): void
    {
        $render = new GatewayBuilder('Test', ['delete']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function drop(Test \$test): void;
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkManyActions(): void
    {
        $render = new GatewayBuilder('Test', ['create', 'other']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function push(Test \$test): void;
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expectedName, string $expectedSingular): void
    {
        $render = new GatewayBuilder($entity, ['create']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expectedName};

interface {$expectedName}Gateway
{
    public function push({$expectedName} \${$expectedSingular}): void;
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
            ['user-password', 'UserPassword', 'userPassword'],
            ['Posts', 'Post', 'post'],
        ];
    }
}
