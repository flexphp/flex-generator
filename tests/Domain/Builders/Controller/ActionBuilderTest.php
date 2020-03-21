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

use FlexPHP\Generator\Domain\Builders\Controller\ActionBuilder;
use FlexPHP\Generator\Domain\Builders\Controller\RequestMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ActionBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $action = 'index';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/"}, methods={"GET"}, name="test.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
        \$requestMessage = new IndexTestRequest(\$request->request->all());




    }

T
        ), $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $action = 'create';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/create"}, methods={"POST"}, name="test.create")
     */
    public function create(Request \$request): Response
    {
        \$requestMessage = new CreateTestRequest(\$request->request->all());




    }

T
        ), $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $action = 'read';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/{id}"}, methods={"GET"}, name="test.read")
     * @Cache(smaxage="10")
     */
    public function read(\$id): Response
    {
        \$requestMessage = new ReadTestRequest(['id' => \$id]);




    }

T
        ), $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $action = 'update';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/update/{id}"}, methods={"PUT"}, name="test.update")
     */
    public function update(Request \$request, \$id): Response
    {
        \$requestMessage = new UpdateTestRequest(\$request->request->all());




    }

T
        ), $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $action = 'delete';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/delete/{id}"}, methods={"DELETE"}, name="test.delete")
     */
    public function delete(\$id): Response
    {
        \$requestMessage = new DeleteTestRequest(['id' => \$id]);




    }

T
        ), $render->build());
    }

    /**
     * @dataProvider getCustomActions
     *
     * @param string $action
     */
    public function testItRenderCustomActionOk($action): void
    {
        $entity = 'FooBar';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/custom_action"}, methods={"POST"}, name="foobar.custom_action")
     */
    public function customAction(Request \$request): Response
    {
        \$requestMessage = new CustomActionFooBarRequest(\$request->request->all());




    }

T
        ), $render->build());
    }

    public function testItRenderToString(): void
    {
        $action = 'index';
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => $action,
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'action' => $action,
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
    /**
     * @Route("/"}, methods={"GET"}, name="test.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
        \$requestMessage = new IndexTestRequest(\$request->request->all());




    }

T
        ), $render);
    }

    public function getCustomActions(): array
    {
        return [
            ['custom_action'],
            ['custom action'],
            ['Custom Action'],
            ['cUSTOM aCtion'],
            ['customAction'],
        ];
    }
}
