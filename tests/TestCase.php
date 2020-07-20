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
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Schema;
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

    protected function getSchema(): SchemaInterface
    {
        return Schema::fromArray(['EntityFoo' => [
            Keyword::TITLE => 'Entity Foo Title',
            Keyword::ATTRIBUTES => [
                [
                    Keyword::NAME => 'lower',
                    Keyword::DATATYPE => 'string',
                    Keyword::CONSTRAINTS => 'pk|ai|minlength:20|maxlength:100',
                ],
                [
                    Keyword::NAME => 'UPPER',
                    Keyword::DATATYPE => 'integer',
                    Keyword::CONSTRAINTS => 'min:2|max:10',
                ],
                [
                    Keyword::NAME => 'PascalCase',
                    Keyword::DATATYPE => 'datetime',
                    Keyword::CONSTRAINTS => 'required',
                ],
                [
                    Keyword::NAME => 'camelCase',
                    Keyword::DATATYPE => 'boolean',
                    Keyword::CONSTRAINTS => '',
                ],
                [
                    Keyword::NAME => 'snake_case',
                    Keyword::DATATYPE => 'text',
                    Keyword::CONSTRAINTS => 'length:100,200',
                ],
            ],
        ]]);
    }

    protected function getSchemaProperties(): array
    {
        return \array_map(function (SchemaAttributeInterface $schemaAttribute) {
            return $schemaAttribute;
        }, $this->getSchema()->attributes());
    }

    protected function getSchemaPropertiesRules(): array
    {
        return \array_reduce(
            $this->getSchema()->attributes(),
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[$schemaAttribute->name()] = (new RuleBuilder(
                    $schemaAttribute->name(),
                    $schemaAttribute->constraints()
                ))->build();

                return $result;
            },
            []
        );
    }

    protected function getSchemaFkRelation(): Schema
    {
        return Schema::fromArray([
            'EntityBar' => [
                Keyword::TITLE => 'Entity Bar Title',
                Keyword::ATTRIBUTES => [
                    [
                        Keyword::NAME => 'foo',
                        Keyword::DATATYPE => 'integer',
                        Keyword::CONSTRAINTS => 'fk:bar,fuz,baz',
                    ],
                    [
                        Keyword::NAME => 'postId',
                        Keyword::DATATYPE => 'integer',
                        Keyword::CONSTRAINTS => 'fk:posts',
                    ],
                ],
            ],
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
