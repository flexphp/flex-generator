<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\SetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

class SetterBuilderTest extends TestCase
{
    public function testItWithDefaultType(): void
    {
        $render = new SetterBuilder([
            'foo' => [],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(string \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
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
        $render = new SetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(string \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
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
        $render = new SetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(int \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
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
        $render = new SetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(array \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
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
        $render = new SetterBuilder([
            'foo' => [
                'type' => $type,
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(bool \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
    }
T
        ), $render->build());
    }

    public function testItWithUnknowType(): void
    {
        $render = new SetterBuilder([
            'foo' => [
                'type' => 'unknow',
            ],
        ]);

        $this->assertEquals(str_replace(
            "\r\n",
            "\n",
            <<<T
    public function setFoo(string \$foo): self
    {
        \$this->foo = \$foo;

        return \$this;
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
