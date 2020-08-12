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

use FlexPHP\Generator\Domain\Builders\Entity\FkGetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FkGetterBuilderTest extends TestCase
{
    public function testItNotRequiredOk(): void
    {
        $render = new FkGetterBuilder('foo', 'Foo');

        $this->assertEquals(<<<T
    public function fooInstance(): ?Foo
    {
        return \$this->fooInstance;
    }
T
, $render->build());
    }

    public function testItRequiredOk(): void
    {
        $render = new FkGetterBuilder('foo', 'Foo');

        $this->assertEquals(<<<T
    public function fooInstance(): ?Foo
    {
        return \$this->fooInstance;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getEntityNameAndFkGetter
     */
    public function testItWithDiffEntityName(
        string $name,
        string $entity,
        string $entityExpected,
        string $getter
    ): void {
        $render = new FkGetterBuilder($name, $entity);

        $this->assertEquals(<<<T
        public function {$getter}Instance(): ?$entityExpected
        {
            return \$this->{$name}Instance;
        }
    T
    , $render->build());
    }

    /**
     * @dataProvider getPropertyNameAndFkGetter
     */
    public function testItWithDiffPropertyName(
        string $name,
        string $type,
        string $nameExpected,
        string $getter
    ): void {
        $render = new FkGetterBuilder($name, $type);

        $this->assertEquals(<<<T
    public function {$getter}Instance(): ?$type
    {
        return \$this->{$nameExpected}Instance;
    }
T
, $render->build());
    }

    public function getEntityNameAndFkGetter(): array
    {
        return [
            ['fk', 'userpassword', 'Userpassword', 'fk'],
            ['fk', 'USERPASSWORD', 'Userpassword', 'fk'],
            ['fk', 'UserPassword', 'UserPassword', 'fk'],
            ['fk', 'userPassword', 'UserPassword', 'fk'],
            ['fk', 'user_password', 'UserPassword', 'fk'],
            ['fk', 'Posts', 'Post', 'fk'],
        ];
    }

    public function getPropertyNameAndFkGetter(): array
    {
        return [
            ['fooname', 'Foo', 'fooname', 'fooname'],
            ['FOONAME', 'Foo', 'fooname', 'fooname'],
            ['FooName', 'Foo', 'fooName', 'fooName'],
            ['fooName', 'Foo', 'fooName', 'fooName'],
            ['foo_name', 'Foo', 'fooName', 'fooName'],
        ];
    }
}
