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

final class EntityBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $name = 'Test';
        $properties = [
            [
                'Name' => 'title',
                'DataType' => 'string',
            ],
            [
                'Name' => 'content',
                'DataType' => 'text',
            ],
            [
                'Name' => 'createdAt',
                'DataType' => 'datetime',
            ],
        ];

        $render = new EntityBuilder($name, $properties);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Entity;

use FlexPHP\Entities\Entity;

final class Test extends Entity
{
    private \$title;
    private \$content;
    private \$createdAt;

    public function getTitle(): string
    {
        return \$this->title;
    }

    public function getContent(): string
    {
        return \$this->content;
    }

    public function getCreatedAt(): string
    {
        return \$this->createdAt;
    }

    public function setTitle(string \$title): void
    {
        \$this->title = \$title;
    }

    public function setContent(string \$content): void
    {
        \$this->content = \$content;
    }

    public function setCreatedAt(string \$createdAt): void
    {
        \$this->createdAt = \$createdAt;
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
        $render = new EntityBuilder($name, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\Entity;

use FlexPHP\Entities\Entity;

final class {$expected} extends Entity
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
        $render = new EntityBuilder('fuz', [
            [
                'Name' => $name,
                'DataType' => 'string',
            ],
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Entity;

use FlexPHP\Entities\Entity;

final class Fuz extends Entity
{
    private \${$expected};

    public function {$getter}(): string
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
            ['fooname', 'fooname', 'setFooname', 'getFooname'],
            ['FOONAME', 'fooname', 'setFooname', 'getFooname'],
            ['FooName', 'fooName', 'setFooName', 'getFooName'],
            ['fooName', 'fooName', 'setFooName', 'getFooName'],
            ['foo_name', 'fooName', 'setFooName', 'getFooName'],
            ['foo-name', 'fooName', 'setFooName', 'getFooName'],
        ];
    }
}
