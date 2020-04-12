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

use FlexPHP\Generator\Domain\Builders\Entity\GetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class GetterBuilderTest extends TestCase
{
    public function testItWithDefaultType(): void
    {
        $render = new GetterBuilder('foo', '');

        $this->assertEquals(<<<T
    public function foo(): string
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeString
     */
    public function testItWithString(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): string
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeDate
     */
    public function testItWithDate(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): \DateTime
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeDateImmutable
     */
    public function testItWithDateImmutable(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): \DateTimeImmutable
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeInt
     */
    public function testItWithInt(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): int
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeArray
     */
    public function testItWithArray(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): array
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeFloat
     */
    public function testItWithFloat(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): float
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getDataTypeBool
     */
    public function testItWithBool(string $dataType): void
    {
        $render = new GetterBuilder('foo', $dataType);

        $this->assertEquals(<<<T
    public function foo(): bool
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    public function testItRequired(): void
    {
        $render = new GetterBuilder('foo', 'string', true);

        $this->assertEquals(<<<T
    public function foo(): string
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    public function testItNotRequired(): void
    {
        $render = new GetterBuilder('foo', 'string', false);

        $this->assertEquals(<<<T
    public function foo(): ?string
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    public function testItWithUnknowType(): void
    {
        $render = new GetterBuilder('foo', 'unknow');

        $this->assertEquals(<<<T
    public function foo(): string
    {
        return \$this->foo;
    }
T
, $render->build());
    }

    /**
     * @dataProvider getPropertyNameAndGetter
     */
    public function testItWithDiffPropertyName(string $name, string $expected, string $getter): void
    {
        $render = new GetterBuilder($name, 'string');

        $this->assertEquals(<<<T
    public function {$getter}(): string
    {
        return \$this->{$expected};
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
            ['double'],
        ];
    }

    public function getDataTypeBool(): array
    {
        return [
            ['bool'],
            ['boolean'],
        ];
    }

    public function getPropertyNameAndGetter(): array
    {
        return [
            ['fooname', 'fooname', 'fooname'],
            ['FOONAME', 'fooname', 'fooname'],
            ['FooName', 'fooName', 'fooName'],
            ['fooName', 'fooName', 'fooName'],
            ['foo_name', 'fooName', 'fooName'],
            ['foo-name', 'fooName', 'fooName'],
        ];
    }
}
