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

use FlexPHP\Generator\Domain\Builders\Controller\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;

class UseCaseBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new UseCaseBuilder([
            'entity' => 'Test',
            'action' => 'index',
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
        \$useCase = new IndexTestUseCase();
        \$response = \$useCase->execute(\$requestMessage);
T), $render->build());
    }
}
