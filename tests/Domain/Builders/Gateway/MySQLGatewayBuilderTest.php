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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.lower as lower',
            'test.UPPER as upper',
            'test.PascalCase as pascalCase',
            'test.camelCase as camelCase',
            'test.snake_case as snakeCase',
        ]);
        \$query->from('`Test`', '`test`');

        \$query->orderBy('test.lower', 'ASC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }
}

T
, $render->build());
    }

    public function testItIndexOperatorOk(): void
    {
        $render = new MySQLGatewayBuilder($this->getSchemaFkWithFilterAndFchars(), ['index']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Request\FindTestBarRequest;
use Domain\Test\Request\FindTestCheckRequest;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        'filter' => DbalCriteriaHelper::OP_START,
        'otherFilter' => DbalCriteriaHelper::OP_EQUALS,
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.id as id',
            'test.filter as filter',
            'test.OtherFilter as otherFilter',
            'test.fchars as fchars',
            'test.fkcheck as fkcheck',
            'test.trim as trim',
            'fchars.baz as `fchars.baz`',
            'fchars.fuz as `fchars.fuz`',
            'fkcheck.id as `fkcheck.id`',
            'fkcheck.fk as `fkcheck.fk`',
        ]);
        \$query->from('`Test`', '`test`');
        \$query->leftJoin('`test`', '`Bar`', '`fchars`', 'test.fchars = fchars.baz');
        \$query->leftJoin('`test`', '`Check`', '`fkcheck`', 'test.fkcheck = fkcheck.id');

        \$query->orderBy('test.id', 'DESC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function filterBars(FindTestBarRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'bar.baz as id',
            'bar.fuz as text',
        ]);
        \$query->from('`Bar`', '`bar`');

        \$query->where('bar.fuz like :bar_fuz');
        \$query->setParameter(':bar_fuz', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function filterChecks(FindTestCheckRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'check.id as id',
            'check.fk as text',
        ]);
        \$query->from('`Check`', '`check`');

        \$query->where('check.fk like :check_fk');
        \$query->setParameter(':check_fk', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function push(Test \$test): string
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Test`');

        \$query->setValue('lower', ':lower');
        \$query->setValue('UPPER', ':upper');
        \$query->setValue('PascalCase', ':pascalCase');
        \$query->setValue('camelCase', ':camelCase');
        \$query->setValue('snake_case', ':snakeCase');

        \$query->setParameter(':lower', \$test->lower(), DB::STRING);
        \$query->setParameter(':upper', \$test->upper(), DB::INTEGER);
        \$query->setParameter(':pascalCase', \$test->pascalCase(), DB::DATETIME_MUTABLE);
        \$query->setParameter(':camelCase', \$test->camelCase(), DB::BOOLEAN);
        \$query->setParameter(':snakeCase', \$test->snakeCase(), DB::TEXT);

        \$query->execute();

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

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Upper\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Upper\Upper;
use Domain\Upper\UpperGateway;

class MySQLUpperGateway implements UpperGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'upper.Foo as foo',
        ]);
        \$query->from('`Upper`', '`upper`');

        \$query->orderBy('upper.Foo', 'ASC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('upper', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(Upper \$upper): string
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Upper`');

        \$query->setValue('Foo', ':foo');

        \$query->setParameter(':foo', \$upper->foo(), DB::STRING);

        \$query->execute();

        return \$upper->foo();
    }

    public function get(Upper \$upper): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'upper.Foo as foo',
        ]);
        \$query->from('`Upper`', '`upper`');
        \$query->where('upper.Foo = :foo');
        \$query->setParameter(':foo', \$upper->foo(), DB::STRING);

        return \$query->execute()->fetch() ?: [];
    }

    public function shift(Upper \$upper): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Upper`');

        \$query->set('Foo', ':foo');

        \$query->setParameter(':foo', \$upper->foo(), DB::STRING);

        \$query->where('Foo = :foo');
        \$query->setParameter(':foo', \$upper->foo(), DB::STRING);

        \$query->execute();
    }

    public function pop(Upper \$upper): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->delete('`Upper`');

        \$query->where('Foo = :foo');
        \$query->setParameter(':foo', \$upper->foo(), DB::STRING);

        \$query->execute();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function get(Test \$test): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.lower as lower',
            'test.UPPER as upper',
            'test.PascalCase as pascalCase',
            'test.camelCase as camelCase',
            'test.snake_case as snakeCase',
        ]);
        \$query->from('`Test`', '`test`');
        \$query->where('test.lower = :lower');
        \$query->setParameter(':lower', \$test->lower(), DB::STRING);

        return \$query->execute()->fetch() ?: [];
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
{$this->header}
namespace Domain\Join\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Join\Join;
use Domain\Join\JoinGateway;
use Domain\Join\Request\FindJoinJoinTableRequest;

class MySQLJoinGateway implements JoinGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function get(Join \$join): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'join.pk as pk',
            'join.field as field',
            'join.joinField as joinField',
            'joinField.fkId as `joinField.fkId`',
            'joinField.fkName as `joinField.fkName`',
        ]);
        \$query->from('`Join`', '`join`');
        \$query->leftJoin('`join`', '`joinTable`', '`joinField`', 'join.joinField = joinField.fkId');
        \$query->where('join.pk = :pk');
        \$query->setParameter(':pk', \$join->pk(), DB::INTEGER);

        return \$query->execute()->fetch() ?: [];
    }

    public function filterJoinTables(FindJoinJoinTableRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'joinTable.fkId as id',
            'joinTable.fkName as text',
        ]);
        \$query->from('`joinTable`', '`joinTable`');

        \$query->where('joinTable.fkName like :joinTable_fkName');
        \$query->setParameter(':joinTable_fkName', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function shift(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Test`');

        \$query->set('lower', ':lower');
        \$query->set('UPPER', ':upper');
        \$query->set('PascalCase', ':pascalCase');
        \$query->set('camelCase', ':camelCase');
        \$query->set('snake_case', ':snakeCase');

        \$query->setParameter(':lower', \$test->lower(), DB::STRING);
        \$query->setParameter(':upper', \$test->upper(), DB::INTEGER);
        \$query->setParameter(':pascalCase', \$test->pascalCase(), DB::DATETIME_MUTABLE);
        \$query->setParameter(':camelCase', \$test->camelCase(), DB::BOOLEAN);
        \$query->setParameter(':snakeCase', \$test->snakeCase(), DB::TEXT);

        \$query->where('lower = :lower');
        \$query->setParameter(':lower', \$test->lower(), DB::STRING);

        \$query->execute();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function pop(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->delete('`Test`');

        \$query->where('lower = :lower');
        \$query->setParameter(':lower', \$test->lower(), DB::STRING);

        \$query->execute();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function getBy(string \$column, \$value): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'lower as lower',
            'UPPER as upper',
            'PascalCase as pascalCase',
            'camelCase as camelCase',
            'snake_case as snakeCase',
        ]);
        \$query->from('`Test`');
        \$query->where("{\$column} = :column");
        \$query->setParameter(':column', \$value);

        return \$query->execute()->fetch() ?: [];
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
{$this->header}
namespace Domain\PostComment\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\PostComment\PostComment;
use Domain\PostComment\PostCommentGateway;
use Domain\PostComment\Request\FindPostCommentBarRequest;
use Domain\PostComment\Request\FindPostCommentPostRequest;
use Domain\PostComment\Request\FindPostCommentUserStatusRequest;

class MySQLPostCommentGateway implements PostCommentGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
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
        \$query->from('`PostComments`', '`postComment`');
        \$query->join('`postComment`', '`Bar`', '`foo`', 'postComment.foo = foo.baz');
        \$query->leftJoin('`postComment`', '`posts`', '`postId`', 'postComment.PostId = postId.id');
        \$query->leftJoin('`postComment`', '`UserStatus`', '`statusId`', 'postComment.StatusId = statusId.id');

        \$query->orderBy('postComment.Pk', 'DESC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('postComment', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(PostComment \$postComment): int
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`PostComments`');

        \$query->setValue('foo', ':foo');
        \$query->setValue('PostId', ':postId');
        \$query->setValue('StatusId', ':statusId');

        \$query->setParameter(':foo', \$postComment->foo(), DB::STRING);
        \$query->setParameter(':postId', \$postComment->postId(), DB::INTEGER);
        \$query->setParameter(':statusId', \$postComment->statusId(), DB::INTEGER);

        \$query->execute();

        return (int)\$query->getConnection()->lastInsertId();
    }

    public function get(PostComment \$postComment): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
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
        \$query->from('`PostComments`', '`postComment`');
        \$query->join('`postComment`', '`Bar`', '`foo`', 'postComment.foo = foo.baz');
        \$query->leftJoin('`postComment`', '`posts`', '`postId`', 'postComment.PostId = postId.id');
        \$query->leftJoin('`postComment`', '`UserStatus`', '`statusId`', 'postComment.StatusId = statusId.id');
        \$query->where('postComment.Pk = :pk');
        \$query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        return \$query->execute()->fetch() ?: [];
    }

    public function shift(PostComment \$postComment): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`PostComments`');

        \$query->set('foo', ':foo');
        \$query->set('PostId', ':postId');
        \$query->set('StatusId', ':statusId');

        \$query->setParameter(':foo', \$postComment->foo(), DB::STRING);
        \$query->setParameter(':postId', \$postComment->postId(), DB::INTEGER);
        \$query->setParameter(':statusId', \$postComment->statusId(), DB::INTEGER);

        \$query->where('Pk = :pk');
        \$query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        \$query->execute();
    }

    public function pop(PostComment \$postComment): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->delete('`PostComments`');

        \$query->where('Pk = :pk');
        \$query->setParameter(':pk', \$postComment->pk(), DB::INTEGER);

        \$query->execute();
    }

    public function filterBars(FindPostCommentBarRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'bar.baz as id',
            'bar.fuz as text',
        ]);
        \$query->from('`Bar`', '`bar`');

        \$query->where('bar.fuz like :bar_fuz');
        \$query->setParameter(':bar_fuz', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function filterPosts(FindPostCommentPostRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'post.id as id',
            'post.name as text',
        ]);
        \$query->from('`posts`', '`post`');

        \$query->where('post.name like :post_name');
        \$query->setParameter(':post_name', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function filterUserStatus(FindPostCommentUserStatusRequest \$request, int \$page, int \$limit): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'userStatus.id as id',
            'userStatus.name as text',
        ]);
        \$query->from('`UserStatus`', '`userStatus`');

        \$query->where('userStatus.name like :userStatus_name');
        \$query->setParameter(':userStatus_name', "%{\$request->term}%");

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.key as key',
            'test.Value as value',
        ]);
        \$query->from('`Test`', '`test`');

        \$query->orderBy('test.Updated', 'DESC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(Test \$test): int
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Test`');

        \$query->setValue('Value', ':value');
        \$query->setValue('Created', ':created');
        \$query->setValue('Updated', ':updated');

        \$query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$query->setParameter(':created', \$test->created() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        \$query->setParameter(':updated', \$test->updated() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$query->execute();

        return (int)\$query->getConnection()->lastInsertId();
    }

    public function shift(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Test`');

        \$query->set('Value', ':value');
        \$query->set('Updated', ':updated');

        \$query->setParameter(':value', \$test->value(), DB::INTEGER);
        \$query->setParameter(':updated', \$test->updated() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$query->where('key = :key');
        \$query->setParameter(':key', \$test->key(), DB::INTEGER);

        \$query->execute();
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
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
        ]);
        \$query->from('`Test`', '`test`');

        \$query->orderBy('test.code', 'ASC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(Test \$test): string
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Test`');

        \$query->setValue('code', ':code');
        \$query->setValue('Name', ':name');
        \$query->setValue('CreatedBy', ':createdBy');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);
        \$query->setParameter(':createdBy', \$test->createdBy(), DB::INTEGER);

        \$query->execute();

        return \$test->code();
    }

    public function get(Test \$test): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
            'test.CreatedBy as createdBy',
            'test.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        \$query->from('`Test`', '`test`');
        \$query->leftJoin('`test`', '`users`', '`createdBy`', 'test.CreatedBy = createdBy.id');
        \$query->leftJoin('`test`', '`users`', '`updatedBy`', 'test.UpdatedBy = updatedBy.id');
        \$query->where('test.code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        return \$query->execute()->fetch() ?: [];
    }

    public function shift(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Test`');

        \$query->set('code', ':code');
        \$query->set('Name', ':name');
        \$query->set('UpdatedBy', ':updatedBy');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);
        \$query->setParameter(':updatedBy', \$test->updatedBy(), DB::INTEGER);

        \$query->where('code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        \$query->execute();
    }
}

T
, $render->build());
    }

    public function testItIndexStringAndOnlyCreatedAt(): void
    {
        $render = new MySQLGatewayBuilder(new Schema('Test', 'bar', [
            new SchemaAttribute('code', 'string', 'pk|required'),
            new SchemaAttribute('Name', 'text', 'required'),
            new SchemaAttribute('CreatedAt', 'datetime', 'ca'),
        ]), ['index', 'create', 'read', 'update']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
        ]);
        \$query->from('`Test`', '`test`');

        \$query->orderBy('test.CreatedAt', 'DESC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(Test \$test): string
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Test`');

        \$query->setValue('code', ':code');
        \$query->setValue('Name', ':name');
        \$query->setValue('CreatedAt', ':createdAt');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);
        \$query->setParameter(':createdAt', \$test->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$query->execute();

        return \$test->code();
    }

    public function get(Test \$test): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
            'test.CreatedAt as createdAt',
        ]);
        \$query->from('`Test`', '`test`');
        \$query->where('test.code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        return \$query->execute()->fetch() ?: [];
    }

    public function shift(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Test`');

        \$query->set('code', ':code');
        \$query->set('Name', ':name');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);

        \$query->where('code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        \$query->execute();
    }
}

T
, $render->build());
    }

    public function testItIndexStringAndOnlyUpdatedAt(): void
    {
        $render = new MySQLGatewayBuilder(new Schema('Test', 'bar', [
            new SchemaAttribute('code', 'string', 'pk|required'),
            new SchemaAttribute('Name', 'text', 'required'),
            new SchemaAttribute('UpdatedAt', 'datetime', 'ua'),
        ]), ['index', 'create', 'read', 'update']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\Helper\DbalCriteriaHelper;
use Domain\Test\Test;
use Domain\Test\TestGateway;

class MySQLTestGateway implements TestGateway
{
    private \$conn;

    private \$operator = [
        //
    ];

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
    }

    public function search(array \$wheres, array \$orders, int \$page, int \$limit, int \$offset): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
        ]);
        \$query->from('`Test`', '`test`');

        \$query->orderBy('test.UpdatedAt', 'DESC');

        \$criteria = new DbalCriteriaHelper(\$query, \$offset);

        foreach (\$wheres as \$column => \$value) {
            \$criteria->getCriteria('test', \$column, \$value, \$this->operator[\$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        \$query->setFirstResult(\$page ? (\$page - 1) * \$limit : 0);
        \$query->setMaxResults(\$limit);

        return \$query->execute()->fetchAll();
    }

    public function push(Test \$test): string
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->insert('`Test`');

        \$query->setValue('code', ':code');
        \$query->setValue('Name', ':name');
        \$query->setValue('UpdatedAt', ':updatedAt');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);
        \$query->setParameter(':updatedAt', \$test->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$query->execute();

        return \$test->code();
    }

    public function get(Test \$test): array
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->select([
            'test.code as code',
            'test.Name as name',
            'test.UpdatedAt as updatedAt',
        ]);
        \$query->from('`Test`', '`test`');
        \$query->where('test.code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        return \$query->execute()->fetch() ?: [];
    }

    public function shift(Test \$test): void
    {
        \$query = \$this->conn->createQueryBuilder();

        \$query->update('`Test`');

        \$query->set('code', ':code');
        \$query->set('Name', ':name');
        \$query->set('UpdatedAt', ':updatedAt');

        \$query->setParameter(':code', \$test->code(), DB::STRING);
        \$query->setParameter(':name', \$test->name(), DB::TEXT);
        \$query->setParameter(':updatedAt', \$test->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);

        \$query->where('code = :code');
        \$query->setParameter(':code', \$test->code(), DB::STRING);

        \$query->execute();
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
{$this->header}
namespace Domain\\{$expectedName}\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use Domain\\{$expectedName}\\{$expectedName};
use Domain\\{$expectedName}\\{$expectedName}Gateway;

class MySQL{$expectedName}Gateway implements {$expectedName}Gateway
{
    private \$conn;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
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
