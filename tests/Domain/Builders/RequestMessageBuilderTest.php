<?php

namespace FlexPHP\Generator\Tests\Domain\Builders;

use FlexPHP\Generator\Domain\Builders\RequestMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class RequestMessageBuilderTest extends TestCase
{
    public function testItRenderObjectOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $requestMessage = new TestRequest($request->request->all());
T), (string)$render);
    }

    public function testItRenderBuildOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $requestMessage = new TestRequest($request->request->all());
T), $render->build());
    }

    public function testItRenderWithActionOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
            'action' => 'action',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $requestMessage = new ActionTestRequest($request->request->all());
T), $render->build());
    }

    public function testItRenderWithActionSpaceOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
            'action' => 'action space',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $requestMessage = new ActionSpaceTestRequest($request->request->all());
T), $render->build());
    }

    public function testItRenderWithActionSlugOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
            'action' => 'action_slug',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $requestMessage = new ActionSlugTestRequest($request->request->all());
T), $render->build());
    }
}
