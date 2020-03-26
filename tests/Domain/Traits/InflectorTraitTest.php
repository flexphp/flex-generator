<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Traits;

use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Tests\TestCase;

final class InflectorTraitTest extends TestCase
{
    public function testItMethods(): void
    {
        $string = 'Hello World';

        $inflector = new class() {
            use InflectorTrait;

            public function camelCase(string $string): string
            {
                return $this->getCamelCase($string);
            }

            public function pascalCase(string $string): string
            {
                return $this->getPascalCase($string);
            }

            public function snakeCase(string $string): string
            {
                return $this->getSnakeCase($string);
            }

            public function singularize(string $string): string
            {
                return $this->getSingularize($string);
            }
        };

        $camelCase = $inflector->camelCase($string);
        $pascalCase = $inflector->pascalCase($string);
        $snakeCase = $inflector->snakeCase($string);
        $singularizeString = $inflector->singularize('worlds');
        $singularizeArray = $inflector->singularize('indices');

        $this->assertEquals('helloWorld', $camelCase);
        $this->assertEquals('HelloWorld', $pascalCase);
        $this->assertEquals('hello_world', $snakeCase);
        $this->assertEquals('world', $singularizeString);
        $this->assertEquals('index', $singularizeArray);
    }
}
