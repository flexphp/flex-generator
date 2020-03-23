<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\RequestMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class RequestMessageBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'index');

        $this->assertEquals(<<<T
        \$requestMessage = new IndexTestRequest(\$request->request->all());
T, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'create');

        $this->assertEquals(<<<T
        \$requestMessage = new CreateTestRequest(\$request->request->all());
T, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'read');

        $this->assertEquals(<<<T
        \$requestMessage = new ReadTestRequest(['id' => \$id]);
T, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'update');

        $this->assertEquals(<<<T
        \$requestMessage = new UpdateTestRequest(\$request->request->all());
T, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'delete');

        $this->assertEquals(<<<T
        \$requestMessage = new DeleteTestRequest(['id' => \$id]);
T, $render->build());
    }

    /**
     * @dataProvider getCustomRequestMessages
     *
     * @param string $action
     * @param mixed $expected
     */
    public function testItRenderCustomRequestMessageOk($action, $expected): void
    {
        $entity = 'FooBar';

        $render = new RequestMessageBuilder($entity, $action);

        $this->assertEquals(<<<T
        \$requestMessage = new {$expected}FooBarRequest(\$request->request->all());
T, $render->build());
    }

    public function testItRenderToString(): void
    {
        $render = new RequestMessageBuilder('Test', 'index');

        $this->assertEquals(<<<T
        \$requestMessage = new IndexTestRequest(\$request->request->all());
T, $render);
    }

    public function getCustomRequestMessages(): array
    {
        return [
            ['custom_action', 'CustomAction'],
            ['custom action', 'CustomAction'],
            ['Custom RequestMessage', 'CustomRequestmessage'],
            ['cUSTOM aCtion', 'CustomAction'],
            ['customRequestMessage', 'CustomRequestMessage'],
        ];
    }
}
