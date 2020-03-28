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
    public function testItOk(string $name, string $expected): void
    {
        $render = new RuleBuilder($name, []);

        $this->assertEquals(<<<T
    public function {$expected}(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItRequiredOk(): void
    {
        $render = new RuleBuilder('foo', [
            'required' => true,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotNull(),
            new NotBlank(),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMinLengthOk(): void
    {
        $render = new RuleBuilder('foo', [
            'minlength' => 20,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Length([
                'min' => 20,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMaxLengthOk(): void
    {
        $render = new RuleBuilder('foo', [
            'maxlength' => 100,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Length([
                'max' => 100,
            ]),
        ], \$constraints));
    }
T, $render->build());
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
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMinCheckLengthOk(): void
    {
        $render = new RuleBuilder('foo', [
            'mincheck' => 3,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Count([
                'min' => 3,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMaxCheckLengthOk(): void
    {
        $render = new RuleBuilder('foo', [
            'maxcheck' => 4,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Count([
                'max' => 4,
            ]),
        ], \$constraints));
    }
T, $render->build());
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
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Count([
                'min' => 1,
                'max' => 5,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMinOk(): void
    {
        $render = new RuleBuilder('foo', [
            'min' => 19,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new LessThanOrEqual([
                'value' => 19,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItMaxOk(): void
    {
        $render = new RuleBuilder('foo', [
            'max' => 21,
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new GreaterThanOrEqual([
                'value' => 21,
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItEqualToOk(): void
    {
        $render = new RuleBuilder('foo', [
            'equalto' => 'EQUAL',
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new EqualTo([
                'value' => 'EQUAL',
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItTypeOk(): void
    {
        $render = new RuleBuilder('foo', [
            'type' => 'string',
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Type([
                'type' => 'string',
            ]),
        ], \$constraints));
    }
T, $render->build());
    }

    public function testItPatternOk(): void
    {
        $render = new RuleBuilder('foo', [
            'pattern' => '/^[a-z_]*$/',
        ]);

        $this->assertEquals(<<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ], \$constraints));
    }
T, $render->build());
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
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotNull(),
            new NotBlank(),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ], \$constraints));
    }
T, $render->build());
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
