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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit): array
    {
        \$this->query->select([
            'test.lower as lower',
            'test.UPPER as upper',
            'test.PascalCase as pascalCase',
            'test.camelCase as camelCase',
            'test.snake_case as snakeCase',
        ]);
        \$this->query->from('`Test`', '`test`');

        foreach(\$wheres as \$column => \$value) {
            if (\$column === 'page' || !\$value) {
                continue;
            }

            \$this->query->where("{\$column} = :{\$column}");
            \$this->query->setParameter(":{\$column}", \$value);
        }

        \$this->query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function push(Test \$test): string
    {
        \$this->query->insert('`Test`');

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

        return \$test->lower();
    }
}

T
, $render->build());
    }

    public function testItPkMayusOk(): void
    {
        $render = new MySQLGatewayBuilder(new Schema('Upper', 'title', [
            new SchemaAttribute('Foo', 'string', 'pk|required'),
        ]), ['index', 'create', 'read', 'update', 'delete']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Upper\Gateway;

use Domain\Upper\Upper;
use Domain\Upper\UpperGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLUpperGateway implements UpperGateway
{
    private $query;

    public function __construct(Connection $conn)
    {
        $this->query = $conn->createQueryBuilder();
    }

    public function search(array $wheres, array $orders, int $page, int $limit): array
    {
        $this->query->select([
            'upper.Foo as foo',
        ]);
        $this->query->from('`Upper`', '`upper`');

        foreach($wheres as $column => $value) {
            if ($column === 'page' || !$value) {
                continue;
            }

            $this->query->where("{$column} = :{$column}");
            $this->query->setParameter(":{$column}", $value);
        }

        $this->query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $this->query->setMaxResults($limit);

        return $this->query->execute()->fetchAll();
    }

    public function push(Upper $upper): string
    {
        $this->query->insert('`Upper`');

        $this->query->setValue('Foo', ':foo');

        $this->query->setParameter(':foo', $upper->foo(), DB::STRING);

        $this->query->execute();

        return $upper->foo();
    }

    public function get(Upper $upper): array
    {
        $this->query->select([
            'upper.Foo as foo',
        ]);
        $this->query->from('`Upper`', '`upper`');
        $this->query->where('upper.Foo = :foo');
        $this->query->setParameter(':foo', $upper->foo(), DB::STRING);

        return $this->query->execute()->fetch() ?: [];
    }

    public function shift(Upper $upper): void
    {
        $this->query->update('`Upper`');

        $this->query->set('Foo', ':foo');

        $this->query->setParameter(':foo', $upper->foo(), DB::STRING);

        $this->query->where('Foo = :foo');
        $this->query->setParameter(':foo', $upper->foo(), DB::STRING);

        $this->query->execute();
    }

    public function pop(Upper $upper): void
    {
        $this->query->delete('`Upper`');

        $this->query->where('Foo = :foo');
        $this->query->setParameter(':foo', $upper->foo(), DB::STRING);

        $this->query->execute();
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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function get(Test \$test): array
    {
        \$this->query->select([
            'test.lower as lower',
            'test.UPPER as upper',
            'test.PascalCase as pascalCase',
            'test.camelCase as camelCase',
            'test.snake_case as snakeCase',
        ]);
        \$this->query->from('`Test`', '`test`');
        \$this->query->where('test.lower = :lower');
        \$this->query->setParameter(':lower', \$test->lower(), DB::STRING);

        return \$this->query->execute()->fetch() ?: [];
    }
}

T
, $render->build());
    }

    public function testItReadJoinOk(): void
    {
        $render = new MySQLGatewayBuilder(new Schema('Join', 'The Test Join', [
            new SchemaAttribute('pk', 'integer', 'pk|ai|required'),
            new SchemaAttribute('field', 'string', 'required'),
            new SchemaAttribute('joinField', 'integer', 'fk:joinTable,fkName,fkId'),
        ]), ['read']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Join\Gateway;

use Domain\Join\Join;
use Domain\Join\JoinGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLJoinGateway implements JoinGateway
{
    private \$query;

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function get(Join \$join): array
    {
        \$this->query->select([
            'join.pk as pk',
            'join.field as field',
            'join.joinField as joinField',
            'joinField.fkId as `joinField.fkId`',
            'joinField.fkName as `joinField.fkName`',
        ]);
        \$this->query->from('`Join`', '`join`');
        \$this->query->leftJoin('`join`', '`joinTable`', '`joinField`', 'join.joinField = joinField.fkId');
        \$this->query->where('join.pk = :pk');
        \$this->query->setParameter(':pk', \$join->pk(), DB::INTEGER);

        return \$this->query->execute()->fetch() ?: [];
    }

    public function filterJoinTables(string \$term, int \$page, int \$limit): array
    {
        \$this->query->select([
            'fkId id',
            'fkName text',
        ]);
        \$this->query->from('`joinTable`');

        \$this->query->where('fkName like :fkName');
        \$this->query->setParameter(':fkName', "%{\$term}%");

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function shift(Test \$test): void
    {
        \$this->query->update('`Test`');

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
        \$this->query->setParameter(':lower', \$test->lower(), DB::STRING);

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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function pop(Test \$test): void
    {
        \$this->query->delete('`Test`');

        \$this->query->where('lower = :lower');
        \$this->query->setParameter(':lower', \$test->lower(), DB::STRING);

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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function getBy(string \$column, \$value): array
    {
        \$this->query->select([
            'lower as lower',
            'UPPER as upper',
            'PascalCase as pascalCase',
            'camelCase as camelCase',
            'snake_case as snakeCase',
        ]);
        \$this->query->from('`Test`');
        \$this->query->where(\$column . ' = :column');
        \$this->query->setParameter(':column', \$value);

        \$data = \$this->query->execute()->fetch();

        return is_array(\$data) ? \$data : [];
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaFkRelation('PostComments'), [
            'index',
            'create',
            'read',
            'update',
            'delete',
            'other',
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\PostComment\Gateway;

use Domain\PostComment\PostComment;
use Domain\PostComment\PostCommentGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;

final class MySQLPostCommentGateway implements PostCommentGateway
{
    private \$query;

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit): array
    {
        \$this->query->select([
            'postComment.Pk as pk',
            'postComment.foo as foo',
            'postComment.PostId as postId',
            'postComment.StatusId as statusId',
            'foo.baz as `foo.baz`',
            'foo.fuz as `foo.fuz`',
            'postId.id as `postId.id`',
            'postId.name as `postId.name`',
            'statusId.id as `statusId.id`',
            'statusId.name as `statusId.name`',
        ]);
        \$this->query->from('`PostComments`', '`postComment`');
        \$this->query->join('`postComment`', '`Bar`', '`foo`', 'postComment.foo = foo.baz');
        \$this->query->leftJoin('`postComment`', '`posts`', '`postId`', 'postComment.PostId = postId.id');
        \$this->query->leftJoin('`postComment`', '`UserStatus`', '`statusId`', 'postComment.StatusId = statusId.id');

        foreach(\$wheres as \$column => \$value) {
            if (\$column === 'page' || !\$value) {
                continue;
            }

            \$this->query->where("{\$column} = :{\$column}");
            \$this->query->setParameter(":{\$column}", \$value);
        }

        \$this->query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }

    public function push(PostComment \$postComment): int
    {
        \$this->query->insert('`PostComments`');

        \$this->query->setValue('foo', ':foo');
        \$this->query->setValue('PostId', ':postId');
        \$this->query->setValue('StatusId', ':statusId');

        \$this->query->setParameter(':foo', \$postComment->foo(), DB::STRING);
        \$this->query->setParameter(':postId', \$postComment->postId(), DB::INTEGER);
        \$this->query->setParameter(':statusId', \$postComment->statusId(), DB::INTEGER);

        \$this->query->execute();

        return (int)\$this->query->getConnection()->lastInsertId();
    }

    public function get(PostComment \$postComment): array
    {
        \$this->query->select([
            'postComment.Pk as pk',
            'postComment.foo as foo',
            'postComment.PostId as postId',
            'postComment.StatusId as statusId',
            'foo.baz as `foo.baz`',
            'foo.fuz as `foo.fuz`',
            'postId.id as `postId.id`',
            'postId.name as `postId.name`',
            'statusId.id as `statusId.id`',
            'statusId.name as `statusId.name`',
        ]);
        \$this->query->from('`PostComments`', '`postComment`');
        \$this->query->join('`postComment`', '`Bar`', '`foo`', 'postComment.foo = foo.baz');
        \$this->query->leftJoin('`postComment`', '`posts`', '`postId`', 'postComment.PostId = postId.id');
        \$this->query->leftJoin('`postComment`', '`UserStatus`', '`statusId`', 'postComment.StatusId = statusId.id');
        \$this->query->where('postComment.Pk = :pk');
        \$this->query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        return \$this->query->execute()->fetch() ?: [];
    }

    public function shift(PostComment \$postComment): void
    {
        \$this->query->update('`PostComments`');

        \$this->query->set('foo', ':foo');
        \$this->query->set('PostId', ':postId');
        \$this->query->set('StatusId', ':statusId');

        \$this->query->setParameter(':foo', \$postComment->foo(), DB::STRING);
        \$this->query->setParameter(':postId', \$postComment->postId(), DB::INTEGER);
        \$this->query->setParameter(':statusId', \$postComment->statusId(), DB::INTEGER);

        \$this->query->where('Pk = :pk');
        \$this->query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        \$this->query->execute();
    }

    public function pop(PostComment \$postComment): void
    {
        \$this->query->delete('`PostComments`');

        \$this->query->where('Pk = :pk');
        \$this->query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        \$this->query->execute();
    }

    public function filterBars(string \$term, int \$page, int \$limit): array
    {
        \$this->query->select([
            'baz id',
            'fuz text',
        ]);
        \$this->query->from('`Bar`');

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
        \$this->query->from('`posts`');

        \$this->query->where('name like :name');
        \$this->query->setParameter(':name', "%{\$term}%");

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }

    public function filterUserStatus(string \$term, int \$page, int \$limit): array
    {
        \$this->query->select([
            'id id',
            'name text',
        ]);
        \$this->query->from('`UserStatus`');

        \$this->query->where('name like :name');
        \$this->query->setParameter(':name', "%{\$term}%");

        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }
}

T
, $render->build());
    }

    public function testItAiAndBlameAt(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaAiAndBlameAt(), ['index', 'create', 'update']);

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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit): array
    {
        \$this->query->select([
            'test.key as key',
            'test.Value as value',
        ]);
        \$this->query->from('`Test`', '`test`');

        foreach(\$wheres as \$column => \$value) {
            if (\$column === 'page' || !\$value) {
                continue;
            }

            \$this->query->where("{\$column} = :{\$column}");
            \$this->query->setParameter(":{\$column}", \$value);
        }

        \$this->query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }

    public function push(Test \$test): int
    {
        \$this->query->insert('`Test`');

        \$this->query->setValue('Value', ':value');
        \$this->query->setValue('Created', ':created');
        \$this->query->setValue('Updated', ':updated');

        \$this->query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$this->query->setParameter(':created', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        \$this->query->setParameter(':updated', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$this->query->execute();

        return (int)\$this->query->getConnection()->lastInsertId();
    }

    public function shift(Test \$test): void
    {
        \$this->query->update('`Test`');

        \$this->query->set('Value', ':value');
        \$this->query->set('Updated', ':updated');

        \$this->query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$this->query->setParameter(':updated', new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$this->query->where('key = :key');
        \$this->query->setParameter(':key', \$test->key(), DB::INTEGER);

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    public function testItBlameBy(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaStringAndBlameBy(), ['index', 'create', 'read', 'update']);

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

    public function __construct(Connection \$conn)
    {
        \$this->query = \$conn->createQueryBuilder();
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit): array
    {
        \$this->query->select([
            'test.code as code',
            'test.Name as name',
        ]);
        \$this->query->from('`Test`', '`test`');

        foreach(\$wheres as \$column => \$value) {
            if (\$column === 'page' || !\$value) {
                continue;
            }

            \$this->query->where("{\$column} = :{\$column}");
            \$this->query->setParameter(":{\$column}", \$value);
        }

        \$this->query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$this->query->setMaxResults(\$limit);

        return \$this->query->execute()->fetchAll();
    }

    public function push(Test \$test): string
    {
        \$this->query->insert('`Test`');

        \$this->query->setValue('code', ':code');
        \$this->query->setValue('Name', ':name');
        \$this->query->setValue('CreatedBy', ':createdBy');

        \$this->query->setParameter(':code', \$test->code(), DB::STRING);
        \$this->query->setParameter(':name', \$test->name(), DB::TEXT);
        \$this->query->setParameter(':createdBy', \$test->createdBy(), DB::INTEGER);

        \$this->query->execute();

        return \$test->code();
    }

    public function get(Test \$test): array
    {
        \$this->query->select([
            'test.code as code',
            'test.Name as name',
            'test.CreatedBy as createdBy',
            'test.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        \$this->query->from('`Test`', '`test`');
        \$this->query->leftJoin('`test`', '`users`', '`createdBy`', 'test.CreatedBy = createdBy.id');
        \$this->query->leftJoin('`test`', '`users`', '`updatedBy`', 'test.UpdatedBy = updatedBy.id');
        \$this->query->where('test.code = :code');
        \$this->query->setParameter(':code', \$test->code(), DB::STRING);

        return \$this->query->execute()->fetch() ?: [];
    }

    public function shift(Test \$test): void
    {
        \$this->query->update('`Test`');

        \$this->query->set('code', ':code');
        \$this->query->set('Name', ':name');
        \$this->query->set('UpdatedBy', ':updatedBy');

        \$this->query->setParameter(':code', \$test->code(), DB::STRING);
        \$this->query->setParameter(':name', \$test->name(), DB::TEXT);
        \$this->query->setParameter(':updatedBy', \$test->updatedBy(), DB::INTEGER);

        \$this->query->where('code = :code');
        \$this->query->setParameter(':code', \$test->code(), DB::STRING);

        \$this->query->execute();
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expectedName): void
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
            ['userpasswords', 'Userpassword'],
            ['USERPASSWORDS', 'Userpassword'],
            ['UserPasswords', 'UserPassword'],
            ['userPasswords', 'UserPassword'],
            ['user_passwords', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
