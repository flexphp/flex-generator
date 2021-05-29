<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\SchemaInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected string $header;

    public static function tearDownAfterClass(): void
    {
        if (\is_dir(self::getOutputFolder())) {
            self::deleteFolder(self::getOutputFolder());
        }
    }

    public function setUp(): void
    {
        \chdir(__DIR__ . '/../');

        $this->header = AbstractBuilder::getHeaderFile();
    }

    protected function getSchema(string $name = 'Test', array $actions = []): SchemaInterface
    {
        return new Schema($name, 'Entity Foo Title', [
            new SchemaAttribute('lower', 'string', 'pk|minlength:20|maxlength:100|required'),
            new SchemaAttribute('UPPER', 'integer', 'min:2|max:10'),
            new SchemaAttribute('PascalCase', 'datetime', 'required'),
            new SchemaAttribute('camelCase', 'boolean', ''),
            new SchemaAttribute('snake_case', 'text', 'length:100,200'),
        ], null, null, $actions);
    }

    protected function getSchemaFkRelation(string $name = 'Test', array $actions = []): Schema
    {
        return new Schema($name, 'Entity Test Title', [
            new SchemaAttribute('Pk', 'integer', 'pk|ai|required'),
            new SchemaAttribute('foo', 'string', 'fk:Bar,fuz,baz|required'),
            new SchemaAttribute('PostId', 'integer', 'fk:posts'),
            new SchemaAttribute('StatusId', 'integer', 'fk:UserStatus'),
        ], null, null, $actions);
    }

    protected function getSchemaAiAndBlameAt(string $name = 'Test', array $actions = []): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('key', 'integer', 'pk|ai|required'),
            new SchemaAttribute('Value', 'integer', 'required'),
            new SchemaAttribute('Created', 'datetime', 'ca'),
            new SchemaAttribute('Updated', 'datetime', 'ua'),
        ], null, null, $actions);
    }

    protected function getSchemaStringAndBlameBy(string $name = 'Test', array $actions = []): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('code', 'string', 'pk|required'),
            new SchemaAttribute('Name', 'text', 'required'),
            new SchemaAttribute('CreatedBy', 'integer', 'cb|fk:users,name'),
            new SchemaAttribute('UpdatedBy', 'integer', 'ub|fk:users,name'),
        ], null, null, $actions);
    }

    protected function getSchemaFkWithFilterAndFchars(string $name = 'Test', array $actions = []): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('id', 'integer', 'pk|ai|required'),
            new SchemaAttribute('filter', 'string', 'filter:ss'),
            new SchemaAttribute('OtherFilter', 'string', 'filter:eq'),
            new SchemaAttribute('fchars', 'string', 'fk:Bar,fuz,baz|fchars:2'),
            new SchemaAttribute('fkcheck', 'string', 'fk:Check,fk|fkcheck'),
            new SchemaAttribute('trim', 'string', 'trim'),
        ], null, null, $actions);
    }

    protected function getSchemaWithFormats(string $name = 'Test'): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('id', 'integer', 'pk|ai|required'),
            new SchemaAttribute('timeago', 'datetime', 'format:timeago'),
            new SchemaAttribute('datetime', 'datetime', 'format:datetime'),
            new SchemaAttribute('money', 'integer', 'format:money'),
        ]);
    }

    protected function getSchemaWithShowAndHide(string $name = 'Test'): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('id', 'integer', 'pk|ai|required|hide:a'),
            new SchemaAttribute('hideInCreate', 'string', 'hide:c'),
            new SchemaAttribute('creator', 'int', 'cb|show:ir'),
            new SchemaAttribute('created', 'datetime', 'ca|show:a'),
        ]);
    }

    protected static function getOutputFolder(): string
    {
        return __DIR__ . '/../src/tmp/skeleton';
    }

    protected static function deleteFolder($dir, $delete = true): void
    {
        if (\is_dir($dir)) {
            $objects = \array_diff(\scandir($dir), ['.', '..']);

            foreach ($objects as $object) {
                if (\is_dir($dir . '/' . $object) && !\is_link($dir . '/' . $object)) {
                    self::deleteFolder($dir . '/' . $object);
                } else {
                    \unlink($dir . '/' . $object);
                }
            }

            if ($delete) {
                \closedir(\opendir($dir));
                \rmdir($dir);
            }
        }
    }
}
