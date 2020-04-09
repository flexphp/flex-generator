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
    public function testItCreateOk(): void
    {
        $render = new GatewayBuilder('Test', ['create']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function persist(Test \$test): void;
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
    public function persist(Test \$test): void;
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
    public function persist({$expectedName} \${$expectedSingular}): void;
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
