<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\SchemaAttribute;

final class RuleBuilderTest extends TestCase
{
    /**
     * @dataProvider getPropertyName
     */
    public function testItNoConstraintsOk(string $name, string $expected): void
    {
        $render = new RuleBuilder(new SchemaAttribute($name, 'string', []));

        $this->assertEquals(<<<T
    private function {$expected}(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItRequiredOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'required' => true,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMinLengthOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'minlength' => 20,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 20,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMaxLengthOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'maxlength' => 100,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'max' => 100,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItLengthOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'length' => [
                'min' => 20,
                'max' => 100,
            ],
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 20,
                'max' => 100,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMinCheckLengthOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'mincheck' => 3,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Count([
                'min' => 3,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMaxCheckLengthOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'maxcheck' => 4,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Count([
                'max' => 4,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItCheckOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'check' => [
                'min' => 1,
                'max' => 5,
            ],
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Count([
                'min' => 1,
                'max' => 5,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMinOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'integer', [
            'min' => 19,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'int',
            ]),
            new Assert\LessThanOrEqual([
                'value' => 19,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItMaxOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'integer', [
            'max' => 21,
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'int',
            ]),
            new Assert\GreaterThanOrEqual([
                'value' => 21,
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItEqualToOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'equalto' => 'EQUAL',
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\EqualTo([
                'value' => 'EQUAL',
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItTypeOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'type' => 'number',
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItTypeImplicitOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', []));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T
, $render->build());
    }

    // public function testItPatternOk(): void
    // {
    //     $this->markTestSkipped('Pattern rule is no available yet');

    //     $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
    //         'pattern' => '/^[a-z_]*$/',
    //     ]));

    //     $this->assertEquals(<<<T
    // private function foo(): array
    // {
    //     return [
    //         new Assert\Regex([
    //             'pattern' => '/^[a-z_]*$/',
    //         ]),
    //     ];
    // }
// T
// , $render->build());
    // }

    public function testItPrimaryKeyOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', 'pk|required'));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T, $render->build());
    }

    public function testItForeingKeyOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', 'fk:table|required'));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T, $render->build());
    }

    public function testItAutoIncremetalOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'integer', 'pk|ai|required'));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }
T, $render->build());
    }

    public function testItTypeFileOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', 'type:file'));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }
T, $render->build());

    }

    public function testItTypeDateAtOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'datetime', []));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\DateTime(),
        ];
    }
T, $render->build());
    }

    public function testItCreatedAtOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'datetime', 'ca'));

        $this->assertEquals('', $render->build());
    }

    public function testItUpdatedAtOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'datetime', 'ua'));

        $this->assertEquals('', $render->build());
    }

    public function testItSomeOk(): void
    {
        $render = new RuleBuilder(new SchemaAttribute('foo', 'string', [
            'required' => true,
            'length' => [
                'min' => 20,
                'max' => 100,
            ],
        ]));

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 20,
                'max' => 100,
            ]),
        ];
    }
T
, $render->build());
    }

    public function getPropertyName(): array
    {
        return [
            ['fooname', 'fooname'],
            ['FOONAME', 'fooname'],
            ['FooName', 'fooName'],
            ['fooName', 'fooName'],
            ['foo_name', 'fooName'],
        ];
    }
}
