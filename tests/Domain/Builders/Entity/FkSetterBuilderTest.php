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

use FlexPHP\Generator\Domain\Builders\Entity\FkSetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FkSetterBuilderTest extends TestCase
{
    public function testItNotRequiredOk(): void
    {
        $render = new FkSetterBuilder('foo', 'Foo', false);

        $this->assertEquals(<<<T
    public function setFooInstance(?Foo \$foo): void
    {
        \$this->fooInstance = \$foo;
    }
T
, $render->build());
    }

    public function testItRequiredOk(): void
    {
        $render = new FkSetterBuilder('foo', 'Foo', true);

        $this->assertEquals(<<<T
    public function setFooInstance(Foo \$foo): void
    {
        \$this->fooInstance = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getEntityNameAndFkSetter
     */
    public function testItWithDiffEntityName(
        string $name,
        string $entity,
        string $entityExpected,
        string $nameEntityExpected,
        string $setter
    ): void {
        $render = new FkSetterBuilder($name, $entity, true);

        $this->assertEquals(<<<T
        public function {$setter}Instance({$entityExpected} \${$nameEntityExpected}): void
        {
            \$this->{$name}Instance = \${$nameEntityExpected};
        }
    T
    , $render->build());
    }

    /**
     * @dataProvider getPropertyNameAndFkSetter
     */
    public function testItWithDiffPropertyName(
        string $name,
        string $type,
        string $nameExpected,
        string $typeExpected,
        string $setter
    ): void {
        $render = new FkSetterBuilder($name, $type, true);

        $this->assertEquals(<<<T
    public function {$setter}Instance($type \${$typeExpected}): void
    {
        \$this->{$nameExpected}Instance = \${$typeExpected};
    }
T
, $render->build());
    }

    public function getEntityNameAndFkSetter(): array
    {
        return [
            ['fk', 'userpassword', 'Userpassword', 'userpassword', 'setFk'],
            ['fk', 'USERPASSWORD', 'Userpassword', 'userpassword', 'setFk'],
            ['fk', 'UserPassword', 'UserPassword', 'userPassword', 'setFk'],
            ['fk', 'userPassword', 'UserPassword', 'userPassword', 'setFk'],
            ['fk', 'user_password', 'UserPassword', 'userPassword', 'setFk'],
            ['fk', 'Posts', 'Post', 'post', 'setFk'],
        ];
    }

    public function getPropertyNameAndFkSetter(): array
    {
        return [
            ['fooname', 'Foo', 'fooname', 'foo', 'setFooname'],
            ['FOONAME', 'Foo', 'fooname', 'foo', 'setFooname'],
            ['FooName', 'Foo', 'fooName', 'foo', 'setFooName'],
            ['fooName', 'Foo', 'fooName', 'foo', 'setFooName'],
            ['foo_name', 'Foo', 'fooName', 'foo', 'setFooName'],
        ];
    }
}
