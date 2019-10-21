<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\ActionBuilder;
use FlexPHP\Generator\Domain\Builders\Controller\RequestMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ActionBuilderTest extends TestCase
{
    public function testItRenderIndexOk()
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

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/"}, methods={"GET"}, name="test.index")
     * @Cache(smaxage="10")
     */
    public function index(Request $request): Response
    {
        $requestMessage = new IndexTestRequest($request->request->all());




    }

T), $render->build());
    }

    public function testItRenderCreateOk()
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

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/create"}, methods={"POST"}, name="test.create")
     */
    public function create(Request $request): Response
    {
        $requestMessage = new CreateTestRequest($request->request->all());




    }

T), $render->build());
    }

    public function testItRenderReadOk()
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

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/{id}"}, methods={"GET"}, name="test.read")
     * @Cache(smaxage="10")
     */
    public function read($id): Response
    {
        $requestMessage = new ReadTestRequest(['id' => $id]);




    }

T), $render->build());
    }

    public function testItRenderUpdateOk()
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

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/update/{id}"}, methods={"PUT"}, name="test.update")
     */
    public function update(Request $request, $id): Response
    {
        $requestMessage = new UpdateTestRequest($request->request->all());




    }

T), $render->build());
    }

    public function testItRenderDeleteOk()
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

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/delete/{id}"}, methods={"DELETE"}, name="test.delete")
     */
    public function delete($id): Response
    {
        $requestMessage = new DeleteTestRequest(['id' => $id]);




    }

T), $render->build());
    }
}
