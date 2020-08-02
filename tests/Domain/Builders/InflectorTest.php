<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders;

use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Tests\TestCase;

final class InflectorTest extends TestCase
{
    public function testItMethods(): void
    {
        $string = 'Hello World';

        $inflector = new Inflector();

        $this->assertEquals('Hello World', $inflector->table($string));
        $this->assertEquals('HelloWorld', $inflector->entity($string));
        $this->assertEquals('HelloWorlds', $inflector->fnPlural($string));
        $this->assertEquals('HelloWorld', $inflector->fnSingular($string));
        $this->assertEquals('helloWorld', $inflector->item($string));
        $this->assertEquals('helloWorlds', $inflector->items($string));
        $this->assertEquals('hello-worlds', $inflector->route($string));
        $this->assertEquals('helloWorld', $inflector->camelProperty($string));
        $this->assertEquals('HelloWorld', $inflector->pascalProperty($string));
        $this->assertEquals('hello_world', $inflector->form($string));
        $this->assertEquals('hello_world', $inflector->action($string));
        $this->assertEquals('HelloWorld', $inflector->pascalAction($string));
        $this->assertEquals('helloWorld', $inflector->camelAction($string));
        $this->assertEquals('hello-world', $inflector->dashAction($string));
        $this->assertEquals('HELLOWORLD', $inflector->role($string));
        $this->assertEquals('post-comments.custom-action', $inflector->routeName('postComment', 'customAction'));
        $this->assertEquals('posts', $inflector->route('POSTS'));
        $this->assertEquals('posts.index', $inflector->routeName('POSTS', 'index'));
        $this->assertEquals('Index', $inflector->pascalAction('index'));
        $this->assertEquals('post-comments:action', $inflector->commandName('postComments', 'action'));
        $this->assertEquals('Post Comment', $inflector->entityTitleSingular('postComments'));
        $this->assertEquals('Post Comments', $inflector->propertyTitle('postComments'));
        $this->assertEquals('post_comments', $inflector->dbName('postComments'));
        $this->assertEquals('postComments', $inflector->jsName('PostComment'));
        $this->assertEquals('PostComments', $inflector->sheetName('postComments'));
        $this->assertEquals('post_comments', $inflector->prototypeName('postComments'));
    }

    public function testItWords(): void
    {
        $strings = [
            'userstatus' => 'userstatus',
            'status' => 'status',
            'vehicles' => 'vehicle',
            'types' => 'type',
            'services' => 'service',
            'indices' => 'indice',
        ];

        $inflector = new Inflector();

        foreach ($strings as $plural => $singular) {
            $this->assertEquals($singular, $inflector->singular($plural));
            $this->assertEquals($plural, $inflector->plural($singular));
        }
    }
}
