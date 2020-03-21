<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Message;

use FlexPHP\Generator\Domain\Builders\Message\ResponseBuilder;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

class ResponseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $action = 'action';
        $entity = 'Fuz';
        $properties = [
            'foo' => [
                Keyword::NAME => 'Foo',
                Keyword::DATATYPE => 'integer',
            ],
            'bar' => [
                Keyword::NAME => 'Bar',
                Keyword::DATATYPE => 'varchar',
            ],
        ];

        $render = new ResponseBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
<?php

namespace Domain\Fuz\Message;

use FlexPHP\Messages\ResponseInterface;

/**
 * Response to action on Fuz.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class ActionFuzResponse implements ResponseInterface
{
    public function __construct(array \$data)
    {
    }
}

T
), $render->build());
    }
}
