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

        $this->assertEquals(<<<'T'
        $requestMessage = new TestRequest($request->request->all());
T, $render);
    }

    public function testItRenderBuildOk()
    {
        $render = new RequestMessageBuilder([
            'entity' => 'Test',
        ]);

        $this->assertEquals(<<<'T'
        $requestMessage = new TestRequest($request->request->all());
T, $render->build());
    }
}
