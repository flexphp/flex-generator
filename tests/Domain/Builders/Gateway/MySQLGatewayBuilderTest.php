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
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class MySQLGatewayBuilderTest extends TestCase
{
    public function testItIndexOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchema(), ['index']);

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
        $render = new MySQLGatewayBuilder($this->getSchema(), ['create']);

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
        $render = new MySQLGatewayBuilder($this->getSchema(), ['read']);

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
        \$this->query->where('lower = :lower');
        \$this->query->setParameter('lower', \$test->lower(), DB::STRING);

        \$register = \$this->query->execute()->fetch();

        return \$register ? \$register : [];
    }
}

T
, $render->build());
    }

    public function testItUpdateOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchema(), ['update']);

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

        \$this->query->where('lower = :lower');
        \$this->query->setParameter('lower', \$test->lower(), DB::STRING);

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItDeleteOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchema(), ['delete']);

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

        \$this->query->where('lower = :lower');
        \$this->query->setParameter('lower', \$test->lower(), DB::STRING);

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItLoginOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchema(), ['login']);

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

        \$data = \$this->query->execute()->fetch();

        return is_array(\$data) ? \$data : [];
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaFkRelation(), ['delete', 'other']);

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

        \$this->query->where('Pk = :pk');
        \$this->query->setParameter('pk', \$test->pk(), DB::INTEGER);

        \$this->query->execute();
    }

    public function filterBars(string \$term, int \$page, int \$limit): array
    {
        \$this->query->select([
            'baz id',
            'fuz text',
        ]);
        \$this->query->from('bars');

        \$this->query->where('fuz like :fuz');
        \$this->query->setParameter(':fuz', "%{\$term}%");

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }

    public function filterPosts(string \$term, int \$page, int \$limit): array
    {
        \$this->query->select([
            'id id',
            'name text',
        ]);
        \$this->query->from('posts');

        \$this->query->where('name like :name');
        \$this->query->setParameter(':name', "%{\$term}%");

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }
}

T
, $render->build());
    }

    public function testItAutoIncrementalAndBlameable(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaAiAndBlame(), ['create', 'update']);

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

        \$this->query->setValue('Value', ':value');
        \$this->query->setValue('Created', ':created');
        \$this->query->setValue('Updated', ':updated');

        \$this->query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$this->query->setParameter(':created', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        \$this->query->setParameter(':updated', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$this->query->execute();
    }

    public function shift(Test \$test): void
    {
        \$this->query->update(\$this->table);

        \$this->query->set('Value', ':value');
        \$this->query->set('Updated', ':updated');

        \$this->query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$this->query->setParameter(':updated', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$this->query->where('key = :key');
        \$this->query->setParameter('key', \$test->key(), DB::INTEGER);

        \$this->query->execute();
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
        $render = new MySQLGatewayBuilder(new Schema($entity, 'bar', []), []);

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
