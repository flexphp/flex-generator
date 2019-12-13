<?php

namespace FlexPHP\Generator\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase
 * @package FlexPHP\Generator\Tests
 */
class TestCase extends PHPUnitTestCase
{
    public function setUp()
    {
        \chdir(__DIR__ . '/../');
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteFolder(__DIR__ . '/../../../src/tmp/skeleton/', false);
    }

    protected static function deleteFolder($dir, $delete = true)
    {
        if (is_dir($dir)) {
            $objects = array_diff(scandir($dir), ['.', '..']);

            foreach ($objects as $object) {
                if (is_dir($dir . '/' . $object) && !is_link($dir . '/' . $object)) {
                    self::deleteFolder($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }

            if ($delete) {
                rmdir($dir);
            }
        }
    }
}
