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

namespace Domain\Test;

final class Test
{
    private \$title;
    private \$content;
    private \$createdAt;

    public function title(): string
    {
        return \$this->title;
    }

    public function content(): string
    {
        return \$this->content;
    }

    public function createdAt(): string
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
        $render = new EntityBuilder('fuz', [
            [
                'Name' => $name,
                'DataType' => 'string',
            ],
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz;

final class Fuz
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
            ['fooname', 'fooname', 'setFooname', 'fooname'],
            ['FOONAME', 'fooname', 'setFooname', 'fooname'],
            ['FooName', 'fooName', 'setFooName', 'fooName'],
            ['fooName', 'fooName', 'setFooName', 'fooName'],
            ['foo_name', 'fooName', 'setFooName', 'fooName'],
            ['foo-name', 'fooName', 'setFooName', 'fooName'],
        ];
    }
}
