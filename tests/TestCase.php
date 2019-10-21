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

    protected function getPathTemplate()
    {
        return \sprintf('%1$s/../src/Domain/BoilerPlates/FlexPHP', __DIR__);
    }
}
