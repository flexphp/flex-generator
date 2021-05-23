<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Factory;

use FlexPHP\Generator\Domain\Builders\Factory\FactoryBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class FactoryBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new FactoryBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Helper\FactoryExtendedTrait;

final class TestFactory
{
    use FactoryExtendedTrait;

    public function make(\$data): Test
    {
        \$test = new Test();

        if (\is_object(\$data)) {
            \$data = (array)\$data;
        }

        if (isset(\$data['lower'])) {
            \$test->setLower((string)\$data['lower']);
        }

        if (isset(\$data['upper'])) {
            \$test->setUpper((int)\$data['upper']);
        }

        if (isset(\$data['pascalCase'])) {
            \$test->setPascalCase(\is_string(\$data['pascalCase']) ? new \DateTime(\$data['pascalCase']) : \$data['pascalCase']);
        }

        if (isset(\$data['camelCase'])) {
            \$test->setCamelCase((bool)\$data['camelCase']);
        }

        if (isset(\$data['snakeCase'])) {
            \$test->setSnakeCase((string)\$data['snakeCase']);
        }

        return \$test;
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new FactoryBuilder($this->getSchemaFkRelation('FkEntity'));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\FkEntity;

use Domain\Bar\BarFactory;
use Domain\Helper\FactoryExtendedTrait;
use Domain\Post\PostFactory;
use Domain\UserStatus\UserStatusFactory;

final class FkEntityFactory
{
    use FactoryExtendedTrait;

    public function make(\$data): FkEntity
    {
        \$fkEntity = new FkEntity();

        if (\is_object(\$data)) {
            \$data = (array)\$data;
        }

        if (isset(\$data['pk'])) {
            \$fkEntity->setPk((int)\$data['pk']);
        }

        if (isset(\$data['foo'])) {
            \$fkEntity->setFoo((string)\$data['foo']);
        }

        if (isset(\$data['postId'])) {
            \$fkEntity->setPostId((int)\$data['postId']);
        }

        if (isset(\$data['statusId'])) {
            \$fkEntity->setStatusId((int)\$data['statusId']);
        }

        if (isset(\$data['foo.baz'])) {
            \$fkEntity->setFooInstance((new BarFactory())->make(\$this->getFkEntity('foo.', \$data)));
        }

        if (isset(\$data['postId.id'])) {
            \$fkEntity->setPostIdInstance((new PostFactory())->make(\$this->getFkEntity('postId.', \$data)));
        }

        if (isset(\$data['statusId.id'])) {
            \$fkEntity->setStatusIdInstance((new UserStatusFactory())->make(\$this->getFkEntity('statusId.', \$data)));
        }

        return \$fkEntity;
    }
}

T
, $render->build());
    }

    public function testItOkBlameByInFk(): void
    {
        $render = new FactoryBuilder(new Schema('Users', 'bar', [
            new SchemaAttribute('createdBy', 'integer', 'cb|fk:Users'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\User;

use Domain\Helper\FactoryExtendedTrait;

final class UserFactory
{
    use FactoryExtendedTrait;

    public function make(\$data): User
    {
        \$user = new User();

        if (\is_object(\$data)) {
            \$data = (array)\$data;
        }

        if (isset(\$data['createdBy'])) {
            \$user->setCreatedBy((int)\$data['createdBy']);
        }

        if (isset(\$data['createdBy.id'])) {
            \$user->setCreatedByInstance((new UserFactory())->make(\$this->getFkEntity('createdBy.', \$data)));
        }

        return \$user;
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expected, string $item): void
    {
        $render = new FactoryBuilder(new Schema($entity, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$expected};

use Domain\Helper\FactoryExtendedTrait;

final class {$expected}Factory
{
    use FactoryExtendedTrait;

    public function make(\$data): $expected
    {
        \${$item} = new {$expected}();

        if (\is_object(\$data)) {
            \$data = (array)\$data;
        }

        return \${$item};
    }
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
            ['Posts', 'Post', 'post'],
        ];
    }
}
