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
        'list' => 'Tests',
    ],
    'label' => [
        'lower' => 'Lower',
        'upper' => 'Upper',
        'pascalCase' => 'Pascal Case',
        'camelCase' => 'Camel Case',
        'snakeCase' => 'Snake Case',
    ],
    'message' => [
        'created' => 'Test created',
        'updated' => 'Test updated',
        'deleted' => 'Test deleted',
    ],
];

T
, $render->build());
    }

    public function testItRenderFilterOk(): void
    {
        $render = new TranslateBuilder(new Schema('Filter', 'filter', [], null, null, ['f']));

        $this->assertEquals(<<<T
<?php

return [
    'entity' => 'Filter',
    'title' => [
        'new' => 'New Filter',
        'edit' => 'Edit Filter',
        'show' => 'Filter Details',
        'list' => 'Filters',
    ],
    'label' => [
    ],
    'message' => [
        'created' => 'Filter created',
        'updated' => 'Filter updated',
        'deleted' => 'Filter deleted',
    ],
    'filter' => [
        'createdAtStart' => 'Date Start',
        'createdAtEnd' => 'Date End',
    ],
];

T
, $render->build());
    }

    /**
     * @dataProvider getTranslateName
     */
    public function testItOkWithDiffTranslateName(string $name, string $singular, string $plural): void
    {
        $render = new TranslateBuilder(new Schema($name, 'bar', []));

        $this->assertEquals(<<<T
<?php

return [
    'entity' => '{$singular}',
    'title' => [
        'new' => 'New {$singular}',
        'edit' => 'Edit {$singular}',
        'show' => '{$singular} Details',
        'list' => '{$plural}',
    ],
    'label' => [
    ],
    'message' => [
        'created' => '{$singular} created',
        'updated' => '{$singular} updated',
        'deleted' => '{$singular} deleted',
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
        'list' => 'Fuzes',
    ],
    'label' => [
        '{$expected}' => '{$expectedLabel}',
    ],
    'message' => [
        'created' => 'Fuz created',
        'updated' => 'Fuz updated',
        'deleted' => 'Fuz deleted',
    ],
];

T
, $render->build());
    }

    public function getTranslateName(): array
    {
        return [
            // entity, singular, plural
            ['userpassword', 'Userpassword', 'Userpasswords'],
            ['USERPASSWORD', 'Userpassword', 'Userpasswords'],
            ['UserPassword', 'User Password', 'User Passwords'],
            ['userPassword', 'User Password', 'User Passwords'],
            ['user_password', 'User Password', 'User Passwords'],
            ['Posts', 'Post', 'Posts'],
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
