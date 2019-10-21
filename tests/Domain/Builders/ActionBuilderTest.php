<?php

namespace FlexPHP\Generator\Tests\Domain\Builders;

use FlexPHP\Generator\Domain\Builders\ActionBuilder;
use FlexPHP\Generator\Domain\Builders\RequestMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ActionBuilderTest extends TestCase
{
    public function testItRenderIndexOk()
    {
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => 'index',
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
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
        $requestMessage = new TestRequest($request->request->all());




    }

T), $render->build());
    }

    public function testItRenderCreateOk()
    {
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => 'create',
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/create"}, methods={"POST"}, name="test.create")
     */
    public function create(Request $request): Response
    {
        $requestMessage = new TestRequest($request->request->all());




    }

T), $render->build());
    }

    public function testItRenderUpdatedOk()
    {
        $entity = 'Test';

        $render = new ActionBuilder([
            'action' => 'update',
            'entity' => $entity,
            'request_message' => (new RequestMessageBuilder([
                'entity' => $entity,
            ]))->build(),
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
    /**
     * @Route("/update"}, methods={"PUT"}, name="test.update")
     */
    public function update(Request $request, $id): Response
    {
        $requestMessage = new TestRequest($request->request->all());




    }

T), $render->build());
    }
}
