<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\GetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

class GetterBuilderTest extends TestCase
{
    public function testItWithDefaultType(): void
    {
        $render = new GetterBuilder([
            'foo' => [],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): string
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    /**
     * @dataProvider getTypeString
     * @param string $type
     * @return void
     */
    public function testItWithString($type): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): string
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    /**
     * @dataProvider getTypeInt
     * @param string $type
     * @return void
     */
    public function testItWithInt($type): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): int
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    /**
     * @dataProvider getTypeArray
     * @param string $type
     * @return void
     */
    public function testItWithArray($type): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): array
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    /**
     * @dataProvider getTypeBool
     * @param string $type
     * @return void
     */
    public function testItWithBool($type): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): bool
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    public function testItWithUnknowType(): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'type' => 'unknow',
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function getFoo(): string
    {
        return \$this->foo;
    }
T
        ), $render->build());
    }

    public function getTypeString(): array
    {
        return [
            ['string'],
            ['text'],
            ['text'],
            ['guid'],
            ['binary'],
            ['blob'],
            ['date'],
            ['datetime'],
            ['datetimetz'],
            ['time'],
        ];
    }

    public function getTypeInt(): array
    {
        return [
            ['smallint'],
            ['integer'],
            ['bigint'],
            ['decimal'],
        ];
    }

    public function getTypeArray(): array
    {
        return [
            ['array'],
        ];
    }

    public function getTypeFloat(): array
    {
        return [
            ['float'],
        ];
    }

    public function getTypeBool(): array
    {
        return [
            ['bool'],
            ['boolean'],
        ];
    }
}
