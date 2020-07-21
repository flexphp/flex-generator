<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Translate;

use FlexPHP\Generator\Domain\Builders\Translate\TranslateBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class TranslateBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new TranslateBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php

return [
    'entity' => 'Test',
    'title' => [
        'new' => 'New Test',
        'edit' => 'Edit Test',
        'show' => 'Test Details',
    ],
    'label' => [
        'lower' => 'Lower',
        'upper' => 'Upper',
        'pascalCase' => 'Pascal Case',
        'camelCase' => 'Camel Case',
        'snakeCase' => 'Snake Case',
    ],
];

T
, $render->build());
    }

    /**
     * @dataProvider getTranslateName
     */
    public function testItOkWithDiffTranslateName(string $name, string $expected): void
    {
        $render = new TranslateBuilder(new Schema($name, 'bar', []));

        $this->assertEquals(<<<T
<?php

return [
    'entity' => '{$expected}',
    'title' => [
        'new' => 'New {$expected}',
        'edit' => 'Edit {$expected}',
        'show' => '{$expected} Details',
    ],
    'label' => [
    ],
];

T
, $render->build());
    }

    /**
     * @dataProvider getPropertyName
     */
    public function testItOkWithDiffPropertyName(string $name, string $expected, string $expectedLabel): void
    {
        $render = new TranslateBuilder(new Schema('fuz', 'bar', [
            new SchemaAttribute($name, 'string'),
        ]));

        $this->assertEquals(<<<T
<?php

return [
    'entity' => 'Fuz',
    'title' => [
        'new' => 'New Fuz',
        'edit' => 'Edit Fuz',
        'show' => 'Fuz Details',
    ],
    'label' => [
        '{$expected}' => '{$expectedLabel}',
    ],
];

T
, $render->build());
    }

    public function getTranslateName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'User Password'],
            ['userPassword', 'User Password'],
            ['user_password', 'User Password'],
            ['user-password', 'User Password'],
            ['Posts', 'Post'],
        ];
    }

    public function getPropertyName(): array
    {
        return [
            ['fooname', 'fooname', 'Fooname'],
            ['FOONAME', 'fooname', 'Fooname'],
            ['FooName', 'fooName', 'Foo Name'],
            ['fooName', 'fooName', 'Foo Name'],
            ['foo_name', 'fooName', 'Foo Name'],
        ];
    }
}
