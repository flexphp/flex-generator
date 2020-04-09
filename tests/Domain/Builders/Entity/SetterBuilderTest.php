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

use FlexPHP\Generator\Domain\Builders\Entity\SetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class SetterBuilderTest extends TestCase
{
    public function testItWithDefaultType(): void
    {
        $render = new SetterBuilder('foo', '');

        $this->assertEquals(<<<T
    public function setFoo(string \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeString
     *
     * @param string $dataType
     */
    public function testItWithString($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(string \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeDate
     *
     * @param string $dataType
     */
    public function testItWithDate($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(\DateTime \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeDateImmutable
     *
     * @param string $dataType
     */
    public function testItWithDateImmutable($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(\DateTimeImmutable \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeInt
     *
     * @param string $dataType
     */
    public function testItWithInt($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(int \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeArray
     *
     * @param string $dataType
     */
    public function testItWithArray($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(array \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeBool
     *
     * @param string $dataType
     */
    public function testItWithBool($dataType): void
    {
        $render = new SetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function setFoo(bool \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    public function testItWithUnknowType(): void
    {
        $render = new SetterBuilder('foo', 'unknow');

        $this->assertEquals(<<<T
    public function setFoo(string \$foo): void
    {
        \$this->foo = \$foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getPropertyNameAndSetter
     */
    public function testItWithDiffPropertyName(string $name, string $expected, string $setter): void
    {
        $render = new SetterBuilder($name, 'string');

        $this->assertEquals(<<<T
    public function {$setter}(string \${$expected}): void
    {
        \$this->{$expected} = \${$expected};
    }
T
, $render->build());
    }

    public function getDataTypeString(): array
    {
        return [
            ['bigint'],
            ['decimal'],
            ['string'],
            ['text'],
            ['text'],
            ['guid'],
            ['binary'],
            ['blob'],
        ];
    }

    public function getDataTypeDate(): array
    {
        return [
            ['date'],
            ['datetime'],
            ['datetimetz'],
            ['time'],
        ];
    }

    public function getDataTypeDateImmutable(): array
    {
        return [
            ['date_immutable'],
            ['datetime_immutable'],
            ['datetimetz_immutable'],
            ['time_immutable'],
        ];
    }

    public function getDataTypeInt(): array
    {
        return [
            ['smallint'],
            ['integer'],
        ];
    }

    public function getDataTypeArray(): array
    {
        return [
            ['array'],
        ];
    }

    public function getDataTypeFloat(): array
    {
        return [
            ['float'],
        ];
    }

    public function getDataTypeBool(): array
    {
        return [
            ['bool'],
            ['boolean'],
        ];
    }

    public function getPropertyNameAndSetter(): array
    {
        return [
            ['fooname', 'fooname', 'setFooname'],
            ['FOONAME', 'fooname', 'setFooname'],
            ['FooName', 'fooName', 'setFooName'],
            ['fooName', 'fooName', 'setFooName'],
            ['foo_name', 'fooName', 'setFooName'],
            ['foo-name', 'fooName', 'setFooName'],
        ];
    }
}
