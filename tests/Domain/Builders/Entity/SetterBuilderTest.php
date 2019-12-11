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
     * @dataProvider getDataTypeString
     * @param string $dataType
     * @return void
     */
    public function testItWithString($dataType): void
    {
        $render = new SetterBuilder([
            'foo' => [
                'DataType' => $dataType,
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
     * @dataProvider getDataTypeInt
     * @param string $dataType
     * @return void
     */
    public function testItWithInt($dataType): void
    {
        $render = new SetterBuilder([
            'foo' => [
                'DataType' => $dataType,
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
     * @dataProvider getDataTypeArray
     * @param string $dataType
     * @return void
     */
    public function testItWithArray($dataType): void
    {
        $render = new SetterBuilder([
            'foo' => [
                'DataType' => $dataType,
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
     * @dataProvider getDataTypeBool
     * @param string $dataType
     * @return void
     */
    public function testItWithBool($dataType): void
    {
        $render = new SetterBuilder([
            'foo' => [
                'DataType' => $dataType,
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
                'DataType' => 'unknow',
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

    public function getDataTypeString(): array
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

    public function getDataTypeInt(): array
    {
        return [
            ['smallint'],
            ['integer'],
            ['bigint'],
            ['decimal'],
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
}
