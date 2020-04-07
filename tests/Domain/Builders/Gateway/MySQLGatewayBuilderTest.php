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

use FlexPHP\Generator\Domain\Builders\Gateway\MySQLGatewayBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class MySQLGatewayBuilderTest extends TestCase
{
    public function testItCreateOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['create'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function persist(Test \$test): void
    {
        \$this->query->insert(\$this->table);
        \$this->query->setValue('lower', '?');
        \$this->query->setParameter(0, \$test->lower());
        \$this->query->setValue('upper', '?');
        \$this->query->setParameter(1, \$test->upper());
        \$this->query->setValue('pascalCase', '?');
        \$this->query->setParameter(2, \$test->pascalCase());
        \$this->query->setValue('camelCase', '?');
        \$this->query->setParameter(3, \$test->camelCase());
        \$this->query->setValue('snakeCase', '?');
        \$this->query->setParameter(4, \$test->snakeCase());

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function _testItOkWithDiffNameEntity(string $entity, string $expectedName, string $expectedPlural): void
    {
        $render = new MySQLGatewayBuilder($entity, [], []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expectedName}\Gateway;

use Domain\\{$expectedName}\\{$expectedName};
use Domain\\{$expectedName}\\{$expectedName}Gateway;
use Doctrine\DBAL\Connection;

final class MySQL{$expectedName}Gateway implements {$expectedName}Gateway
{
    private \$query;
    private \$table = '{$expectedPlural}';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpasswords', 'Userpassword', 'userpasswords'],
            ['USERPASSWORDS', 'Userpassword', 'userpasswords'],
            ['UserPasswords', 'UserPassword', 'user_passwords'],
            ['userPasswords', 'UserPassword', 'user_passwords'],
            ['user_passwords', 'UserPassword', 'user_passwords'],
            ['user-passwords', 'UserPassword', 'user_passwords'],
            ['Posts', 'Post', 'posts'],
        ];
    }
}
