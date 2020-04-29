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
    public function testItIndexOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['index'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function search(array \$wheres, array \$orders, int \$limit): array
    {
        \$this->query->select([
            'lower' => 'lower',
            'UPPER' => 'upper',
            'PascalCase' => 'pascalCase',
            'camelCase' => 'camelCase',
            'snake_case' => 'snakeCase',
        ]);
        \$this->query->from(\$this->table);

        foreach(\$wheres as \$column => \$value) {
            if (!\$value) {
                continue;
            }

            \$this->query->where(\$column . ' = :' . \$column);
            \$this->query->setParameter(\$column, \$value);
        }

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }
}

T
, $render->build());
    }

    public function testItCreateOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['create'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function push(Test \$test): void
    {
        \$this->query->insert(\$this->table);

        \$this->query->setValue('lower', ':lower');
        \$this->query->setValue('UPPER', ':upper');
        \$this->query->setValue('PascalCase', ':pascalCase');
        \$this->query->setValue('camelCase', ':camelCase');
        \$this->query->setValue('snake_case', ':snakeCase');

        \$this->query->setParameter(':lower', \$test->lower(), DB::STRING);
        \$this->query->setParameter(':upper', \$test->upper(), DB::INTEGER);
        \$this->query->setParameter(':pascalCase', \$test->pascalCase(), DB::DATETIME_MUTABLE);
        \$this->query->setParameter(':camelCase', \$test->camelCase(), DB::BOOLEAN);
        \$this->query->setParameter(':snakeCase', \$test->snakeCase(), DB::TEXT);

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItReadOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['read'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function get(Test \$test): array
    {
        \$this->query->select([
            'lower' => 'lower',
            'UPPER' => 'upper',
            'PascalCase' => 'pascalCase',
            'camelCase' => 'camelCase',
            'snake_case' => 'snakeCase',
        ]);
        \$this->query->from(\$this->table);
        \$this->query->where('Id = :id');
        \$this->query->setParameter('id', \$test->id());

        return \$this->query->execute()->fetch();
    }
}

T
, $render->build());
    }

    public function testItUpdateOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['update'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function shift(Test \$test): void
    {
        \$this->query->update(\$this->table);

        \$this->query->set('lower', ':lower');
        \$this->query->set('UPPER', ':upper');
        \$this->query->set('PascalCase', ':pascalCase');
        \$this->query->set('camelCase', ':camelCase');
        \$this->query->set('snake_case', ':snakeCase');

        \$this->query->setParameter(':lower', \$test->lower(), DB::STRING);
        \$this->query->setParameter(':upper', \$test->upper(), DB::INTEGER);
        \$this->query->setParameter(':pascalCase', \$test->pascalCase(), DB::DATETIME_MUTABLE);
        \$this->query->setParameter(':camelCase', \$test->camelCase(), DB::BOOLEAN);
        \$this->query->setParameter(':snakeCase', \$test->snakeCase(), DB::TEXT);

        \$this->query->where('Id = :id');
        \$this->query->setParameter('id', \$test->id());

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItDeleteOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['delete'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function pop(Test \$test): void
    {
        \$this->query->delete(\$this->table);

        \$this->query->where('Id = :id');
        \$this->query->setParameter('id', \$test->id());

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItLoginOk(): void
    {
        $render = new MySQLGatewayBuilder('Test', ['login'], $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Gateway;

use Domain\Test\Test;
use Domain\Test\TestGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLTestGateway implements TestGateway
{
    private \$query;
    private \$table = 'tests';

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function getBy(string \$column, \$value): array
    {
        \$this->query->select([
            'lower' => 'lower',
            'UPPER' => 'upper',
            'PascalCase' => 'pascalCase',
            'camelCase' => 'camelCase',
            'snake_case' => 'snakeCase',
        ]);
        \$this->query->from(\$this->table);
        \$this->query->where(\$column . ' = :column');
        \$this->query->setParameter('column', \$value);

        return \$this->query->execute()->fetch();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expectedName, string $expectedPlural): void
    {
        $render = new MySQLGatewayBuilder($entity, [], []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expectedName}\Gateway;

use Domain\\{$expectedName}\\{$expectedName};
use Domain\\{$expectedName}\\{$expectedName}Gateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

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
