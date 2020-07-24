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

use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public static function tearDownAfterClass(): void
    {
        if (\is_dir(self::getOutputFolder())) {
            self::deleteFolder(self::getOutputFolder());
        }
    }

    public function setUp(): void
    {
        \chdir(__DIR__ . '/../');
    }

    protected function getSchema(string $name = 'Test'): SchemaInterface
    {
        return new Schema($name, 'Entity Foo Title', [
            new SchemaAttribute('lower', 'string', 'pk|minlength:20|maxlength:100|required'),
            new SchemaAttribute('UPPER', 'integer', 'min:2|max:10'),
            new SchemaAttribute('PascalCase', 'datetime', 'required'),
            new SchemaAttribute('camelCase', 'boolean', ''),
            new SchemaAttribute('snake_case', 'text', 'length:100,200'),
        ]);
    }

    protected function getSchemaFkRelation(string $name = 'Test'): Schema
    {
        return new Schema($name, 'Entity Test Title', [
            new SchemaAttribute('Pk', 'integer', 'pk|ai|required'),
            new SchemaAttribute('foo', 'string', 'fk:Bar,fuz,baz|required'),
            new SchemaAttribute('PostId', 'integer', 'fk:posts'),
        ]);
    }

    protected function getSchemaAiAndBlame(string $name = 'Test'): Schema
    {
        return new Schema($name, 'bar', [
            new SchemaAttribute('key', 'integer', 'pk|ai|required'),
            new SchemaAttribute('Value', 'integer', 'required'),
            new SchemaAttribute('Created', 'datetime', 'ca'),
            new SchemaAttribute('Updated', 'datetime', 'ua'),
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
