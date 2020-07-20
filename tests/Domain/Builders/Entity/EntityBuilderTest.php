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
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Schema;

final class EntityBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new EntityBuilder('Test', $this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

final class Test
{
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

    public function pascalCase(): \DateTime
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

    public function setUpper(int \$upper): void
    {
        \$this->upper = \$upper;
    }

    public function setPascalCase(\DateTime \$pascalCase): void
    {
        \$this->pascalCase = \$pascalCase;
    }

    public function setCamelCase(bool \$camelCase): void
    {
        \$this->camelCase = \$camelCase;
    }

    public function setSnakeCase(string \$snakeCase): void
    {
        \$this->snakeCase = \$snakeCase;
    }
}

T
, $render->build());
    }

    public function testItUserEntityInSymfonyOk(): void
    {
        $render = new EntityBuilder('User');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\User;

use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
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
        return ['ROLE_ADMIN', 'ROLE_USER'];
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
        $render = new EntityBuilder($name);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

final class {$expected}
{
}

T
, $render->build());
    }

    /**
     * @dataProvider getPropertyName
     */
    public function testItOkWithDiffPropertyName(string $name, string $expected, string $setter, string $getter): void
    {
        $render = new EntityBuilder('fuz', Schema::fromArray([
            'EntityBar' => [
                Keyword::TITLE => 'Entity Bar Title',
                Keyword::ATTRIBUTES => [
                    [
                        Keyword::NAME => $name,
                        Keyword::DATATYPE => 'string',
                    ],
                ],
            ],
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz;

final class Fuz
{
    private \${$expected};

    public function {$getter}(): ?string
    {
        return \$this->{$expected};
    }

    public function {$setter}(string \${$expected}): void
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
            ['user-password', 'UserPassword'],
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
