<?php

namespace FlexPHP\Generator\Tests\Domain\Builders;

use FlexPHP\Generator\Tests\TestCase;

class RequestMessageBuilderTest extends TestCase
{
    private function getFileTemplate()
    {
        return 'Request.php.twig';
    }

    protected function getPathTemplate()
    {
        return \sprintf('%1$s/Message/', parent::getPathTemplate());
    }

    public function testItRenderOk()
    {
        $pathTemplates = $this->getPathTemplate();

        $loader = new \Twig\Loader\FilesystemLoader($pathTemplates);
        $twig = new \Twig\Environment($loader);

        $expected = <<<'T'
        $requestMessage = new TestRequest($request->request->all());
T;
        $render = $twig->render($this->getFileTemplate(), [
            'entity' => 'Test',
        ]);

        $this->assertEquals($expected, $render);
    }
}
