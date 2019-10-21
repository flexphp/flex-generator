<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;

class UseCaseBuilderTest extends TestCase
{
    public function testItRenderIndexOk()
    {
        $render = new UseCaseBuilder([
            'entity' => 'Test',
            'action' => 'index',
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
        $useCase = new IndexTestUseCase();
        $response = $useCase->execute($requestMessage);
T), $render->build());
    }
}
