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

            public function dashCase(string $string): string
            {
                return $this->getDashCase($string);
            }

            public function singularize(string $string): string
            {
                return $this->getSingularize($string);
            }

            public function pluralize(string $string): string
            {
                return $this->getPluralize($string);
            }
        };

        $this->assertEquals('helloWorld', $inflector->camelCase($string));
        $this->assertEquals('HelloWorld', $inflector->pascalCase($string));
        $this->assertEquals('hello_world', $inflector->snakeCase($string));
        $this->assertEquals('hello-world', $inflector->dashCase($string));
        $this->assertEquals('world', $inflector->singularize('worlds'));
        $this->assertEquals('index', $inflector->singularize('indices'));
        $this->assertEquals('worlds', $inflector->pluralize('world'));
        $this->assertEquals('indicies', $inflector->pluralize('index'));
    }
}
