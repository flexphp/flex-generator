<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\ResponseMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ResponseMessageBuilderTest extends TestCase
{
    public function testItRenderWithActionOk()
    {
        $render = new ResponseMessageBuilder([
            'entity' => 'Test',
            'action' => 'action',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        return new Response($response);
T), $render->build());
    }
}
