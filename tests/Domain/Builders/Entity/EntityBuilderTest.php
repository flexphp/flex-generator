<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class EntityBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new EntityBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Helper\ToArrayTrait;

final class Test
{
    use ToArrayTrait;

    private \$lower;

    private \$upper;

    private \$pascalCase;

    private \$camelCase;

    private \$snakeCase;

    public function lower(): ?string
    {
        return \$this->lower;
    }

    public function upper(): ?int
    {
        return \$this->upper;
    }

    public function pascalCase(): ?\DateTime
    {
        return \$this->pascalCase;
    }

    public function camelCase(): ?bool
    {
        return \$this->camelCase;
    }

    public function snakeCase(): ?string
    {
        return \$this->snakeCase;
    }

    public function setLower(string \$lower): void
    {
        \$this->lower = \$lower;
    }

    public function setUpper(?int \$upper): void
    {
        \$this->upper = \$upper;
    }

    public function setPascalCase(\DateTime \$pascalCase): void
    {
        \$this->pascalCase = \$pascalCase;
    }

    public function setCamelCase(?bool \$camelCase): void
    {
        \$this->camelCase = \$camelCase;
    }

    public function setSnakeCase(?string \$snakeCase): void
    {
        \$this->snakeCase = \$snakeCase;
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new EntityBuilder($this->getSchemaFkRelation());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Bar\Bar;
use Domain\Helper\ToArrayTrait;
use Domain\Post\Post;
use Domain\UserStatus\UserStatus;

final class Test
{
    use ToArrayTrait;

    private \$pk;

    private \$foo;

    private \$postId;

    private \$statusId;

    private \$fooInstance;

    private \$postIdInstance;

    private \$statusIdInstance;

    public function pk(): ?int
    {
        return \$this->pk;
    }

    public function foo(): ?string
    {
        return \$this->foo;
    }

    public function postId(): ?int
    {
        return \$this->postId;
    }

    public function statusId(): ?int
    {
        return \$this->statusId;
    }

    public function fooInstance(): Bar
    {
        return \$this->fooInstance ?: new Bar;
    }

    public function postIdInstance(): ?Post
    {
        return \$this->postIdInstance;
    }

    public function statusIdInstance(): ?UserStatus
    {
        return \$this->statusIdInstance;
    }

    public function setPk(int \$pk): void
    {
        \$this->pk = \$pk;
    }

    public function setFoo(string \$foo): void
    {
        \$this->foo = \$foo;
    }

    public function setPostId(?int \$postId): void
    {
        \$this->postId = \$postId;
    }

    public function setStatusId(?int \$statusId): void
    {
        \$this->statusId = \$statusId;
    }

    public function setFooInstance(Bar \$bar): void
    {
        \$this->fooInstance = \$bar;
    }

    public function setPostIdInstance(?Post \$post): void
    {
        \$this->postIdInstance = \$post;
    }

    public function setStatusIdInstance(?UserStatus \$userStatus): void
    {
        \$this->statusIdInstance = \$userStatus;
    }
}

T
, $render->build());
    }

    public function testItBlameByOk(): void
    {
        $render = new EntityBuilder($this->getSchemaStringAndBlameBy());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Helper\ToArrayTrait;
use Domain\User\User;

final class Test
{
    use ToArrayTrait;

    private \$code;

    private \$name;

    private \$createdBy;

    private \$updatedBy;

    private \$createdByInstance;

    private \$updatedByInstance;

    public function code(): ?string
    {
        return \$this->code;
    }

    public function name(): ?string
    {
        return \$this->name;
    }

    public function createdBy(): ?int
    {
        return \$this->createdBy;
    }

    public function updatedBy(): ?int
    {
        return \$this->updatedBy;
    }

    public function createdByInstance(): ?User
    {
        return \$this->createdByInstance;
    }

    public function updatedByInstance(): ?User
    {
        return \$this->updatedByInstance;
    }

    public function setCode(string \$code): void
    {
        \$this->code = \$code;
    }

    public function setName(string \$name): void
    {
        \$this->name = \$name;
    }

    public function setCreatedBy(?int \$createdBy): void
    {
        \$this->createdBy = \$createdBy;
    }

    public function setUpdatedBy(?int \$updatedBy): void
    {
        \$this->updatedBy = \$updatedBy;
    }

    public function setCreatedByInstance(?User \$user): void
    {
        \$this->createdByInstance = \$user;
    }

    public function setUpdatedByInstance(?User \$user): void
    {
        \$this->updatedByInstance = \$user;
    }
}

T
, $render->build());
    }

    public function testItUserEntityInSymfonyOk(): void
    {
        $render = new EntityBuilder(new Schema('User', 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\User;

use Domain\Helper\ToArrayTrait;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    use ToArrayTrait;

    public function getUsername()
    {
        return \$this->name();
    }

    public function getPassword()
    {
        return \$this->password();
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return (new UserRbac())->getRoles(\$this->email());
    }

    public function eraseCredentials()
    {
        return true;
    }
}

T
, $render->build());
    }

    public function testItSelfReferenceOk(): void
    {
        $render = new EntityBuilder(new Schema('Tests', 'bar', [
            new SchemaAttribute('id', 'integer', 'pk|ai|required'),
            new SchemaAttribute('parent', 'integer', 'fk:Tests'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Helper\ToArrayTrait;

final class Test
{
    use ToArrayTrait;

    private \$id;

    private \$parent;

    private \$parentInstance;

    public function id(): ?int
    {
        return \$this->id;
    }

    public function parent(): ?int
    {
        return \$this->parent;
    }

    public function parentInstance(): ?self
    {
        return \$this->parentInstance;
    }

    public function setId(int \$id): void
    {
        \$this->id = \$id;
    }

    public function setParent(?int \$parent): void
    {
        \$this->parent = \$parent;
    }

    public function setParentInstance(?self \$test): void
    {
        \$this->parentInstance = \$test;
    }
}

T
, $render->build());
    }

    public function testItUsingDefaultValues(): void
    {
        $render = new EntityBuilder(new Schema('Tests', 'bar', [
            new SchemaAttribute('id', 'integer', 'pk|ai|required'),
            new SchemaAttribute('control', 'string'),
            new SchemaAttribute('integer', 'integer', 'default:0'),
            new SchemaAttribute('float', 'double', 'default:0.1'),
            new SchemaAttribute('negative', 'integer', 'default:-1'),
            new SchemaAttribute('string', 'string', 'default:S'),
            new SchemaAttribute('empty', 'string', 'default:'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Domain\Helper\ToArrayTrait;

final class Test
{
    use ToArrayTrait;

    private \$id;

    private \$control;

    private \$integer = 0;

    private \$float = 0.1;

    private \$negative = -1;

    private \$string = 'S';

    private \$empty = '';

    public function id(): ?int
    {
        return \$this->id;
    }

    public function control(): ?string
    {
        return \$this->control;
    }

    public function integer(): ?int
    {
        return \$this->integer;
    }

    public function float(): ?float
    {
        return \$this->float;
    }

    public function negative(): ?int
    {
        return \$this->negative;
    }

    public function string(): ?string
    {
        return \$this->string;
    }

    public function empty(): ?string
    {
        return \$this->empty;
    }

    public function setId(int \$id): void
    {
        \$this->id = \$id;
    }

    public function setControl(?string \$control): void
    {
        \$this->control = \$control;
    }

    public function setInteger(?int \$integer): void
    {
        \$this->integer = \$integer;
    }

    public function setFloat(?float \$float): void
    {
        \$this->float = \$float;
    }

    public function setNegative(?int \$negative): void
    {
        \$this->negative = \$negative;
    }

    public function setString(?string \$string): void
    {
        \$this->string = \$string;
    }

    public function setEmpty(?string \$empty): void
    {
        \$this->empty = \$empty;
    }
}

T
, $render->build());
    }

    public function testItOkBlameByInFk(): void
    {
        $render = new EntityBuilder(new Schema('Users', 'bar', [
            new SchemaAttribute('createdBy', 'integer', 'cb|fk:Users'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\User;

use Domain\Helper\ToArrayTrait;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    use ToArrayTrait;

    private \$createdBy;

    private \$createdByInstance;

    public function createdBy(): ?int
    {
        return \$this->createdBy;
    }

    public function createdByInstance(): ?self
    {
        return \$this->createdByInstance;
    }

    public function setCreatedBy(?int \$createdBy): void
    {
        \$this->createdBy = \$createdBy;
    }

    public function setCreatedByInstance(?self \$user): void
    {
        \$this->createdByInstance = \$user;
    }

    public function getUsername()
    {
        return \$this->name();
    }

    public function getPassword()
    {
        return \$this->password();
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return (new UserRbac())->getRoles(\$this->email());
    }

    public function eraseCredentials()
    {
        return true;
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffEntityName(string $name, string $expected): void
    {
        $render = new EntityBuilder(new Schema($name, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$expected};

use Domain\Helper\ToArrayTrait;

final class {$expected}
{
    use ToArrayTrait;
}

T
, $render->build());
    }

    /**
     * @dataProvider getPropertyName
     */
    public function testItOkWithDiffPropertyName(string $name, string $expected, string $setter, string $getter): void
    {
        $render = new EntityBuilder(new Schema('fuz', 'bar', [
            new SchemaAttribute($name, 'string'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Fuz;

use Domain\Helper\ToArrayTrait;

final class Fuz
{
    use ToArrayTrait;

    private \${$expected};

    public function {$getter}(): ?string
    {
        return \$this->{$expected};
    }

    public function {$setter}(?string \${$expected}): void
    {
        \$this->{$expected} = \${$expected};
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

    public function getPropertyName(): array
    {
        return [
            ['fooname', 'fooname', 'setFooname', 'fooname'],
            ['FOONAME', 'fooname', 'setFooname', 'fooname'],
            ['FooName', 'fooName', 'setFooName', 'fooName'],
            ['fooName', 'fooName', 'setFooName', 'fooName'],
            ['foo_name', 'fooName', 'setFooName', 'fooName'],
        ];
    }
}
