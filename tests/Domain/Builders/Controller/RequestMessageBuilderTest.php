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

final class RequestMessageBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOk(string $entity, string $expected): void
    {
        $render = new RequestMessageBuilder($entity, 'index');

        $this->assertEquals(<<<T
        \$request = new Index{$expected}Request(\$request->request->all());
T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'index');

        $this->assertEquals(<<<T
        \$request = new IndexTestRequest(\$request->request->all());
T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'create');

        $this->assertEquals(<<<T
        \$form = \$this->createForm(TestType::class);
        \$form->handleRequest(\$request);

        \$request = new CreateTestRequest(\$form->getData());
T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'read');

        $this->assertEquals(<<<T
        \$request = new ReadTestRequest(\$id);
T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'update');

        $this->assertEquals(<<<T
        \$form = \$this->createForm(TestType::class);
        \$form->submit(\$request->request->get(\$form->getName()));
        \$form->handleRequest(\$request);

        \$request = new UpdateTestRequest(\$form->getData());
T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new RequestMessageBuilder('Test', 'delete');

        $this->assertEquals(<<<T
        \$request = new DeleteTestRequest(\$id);
T
, $render->build());
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
        \$request = new {$expected}FooBarRequest(\$request->request->all());
T
, $render->build());
    }

    public function testItRenderToString(): void
    {
        $render = new RequestMessageBuilder('Test', 'index');

        $this->assertEquals(<<<T
        \$request = new IndexTestRequest(\$request->request->all());
T
, $render);
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

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
