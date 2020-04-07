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

final class RuleBuilderTest extends TestCase
{
    /**
     * @dataProvider getPropertyName
     */
    public function testItNoConstraintsOk(string $name): void
    {
        $render = new RuleBuilder($name, []);

        $this->assertEquals('', $render->build());
    }

    public function testItRequiredOk(): void
    {
        $render = new RuleBuilder('foo', [
            'required' => true,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
        ];
    }
T
, $render->build());
    }

    public function testItMinLengthOk(): void
    {
        $render = new RuleBuilder('foo', [
            'minlength' => 20,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'maxlength' => 100,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'length' => [
                'min' => 20,
                'max' => 100,
            ],
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'mincheck' => 3,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'maxcheck' => 4,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'check' => [
                'min' => 1,
                'max' => 5,
            ],
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'min' => 19,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'max' => 21,
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'equalto' => 'EQUAL',
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
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
        $render = new RuleBuilder('foo', [
            'type' => 'string',
        ]);

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

    public function testItPatternOk(): void
    {
        $render = new RuleBuilder('foo', [
            'pattern' => '/^[a-z_]*$/',
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ];
    }
T
, $render->build());
    }

    public function testItSomeOk(): void
    {
        $render = new RuleBuilder('foo', [
            'required' => true,
            'pattern' => '/^[a-z_]*$/',
            'length' => [
                'min' => 20,
                'max' => 100,
            ],
        ]);

        $this->assertEquals(<<<T
    private function foo(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Length([
                'min' => 20,
                'max' => 100,
            ]),
            new Assert\Regex([
                'pattern' => '/^[a-z_]*$/',
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
            ['foo-name', 'fooName'],
        ];
    }
}
