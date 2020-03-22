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

use FlexPHP\Generator\Domain\Builders\Controller\ResponseMessageBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ResponseMessageBuilderTest extends TestCase
{
    public function testItRenderWithActionOk(): void
    {
        $render = new ResponseMessageBuilder([
            'entity' => 'Test',
            'action' => 'action',
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
        return new Response(\$response);
T), $render->build());
    }
}
