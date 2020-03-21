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

class GetterBuilderTest extends TestCase
{
    public function testItWithDefaultType(): void
    {
        $render = new GetterBuilder([
            'foo' => [],
        ]);

        $this->assertEquals(\str_replace(
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
     * @dataProvider getDataTypeString
     *
     * @param string $dataType
     */
    public function testItWithString($dataType): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'DataType' => $dataType,
            ],
        ]);

        $this->assertEquals(\str_replace(
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
     * @dataProvider getDataTypeInt
     *
     * @param string $dataType
     */
    public function testItWithInt($dataType): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'DataType' => $dataType,
            ],
        ]);

        $this->assertEquals(\str_replace(
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
     * @dataProvider getDataTypeArray
     *
     * @param string $dataType
     */
    public function testItWithArray($dataType): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'DataType' => $dataType,
            ],
        ]);

        $this->assertEquals(\str_replace(
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
     * @dataProvider getDataTypeBool
     *
     * @param string $dataType
     */
    public function testItWithBool($dataType): void
    {
        $render = new GetterBuilder([
            'foo' => [
                'DataType' => $dataType,
            ],
        ]);

        $this->assertEquals(\str_replace(
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
                'DataType' => 'unknow',
            ],
        ]);

        $this->assertEquals(\str_replace(
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
