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

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public static function tearDownAfterClass(): void
    {
        self::deleteFolder(self::getOutputFolder(), false);
    }

    public function setUp(): void
    {
        \chdir(__DIR__ . '/../');
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
                \rmdir($dir);
            }
        }
    }
}
