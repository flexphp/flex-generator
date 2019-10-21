<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Message;

use FlexPHP\Generator\Domain\Builders\Message\ResponseBuilder;
use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Tests\TestCase;

class ResponseBuilderTest extends TestCase
{
    public function testItRenderOk()
    {
        $action = 'action';
        $entity = 'Fuz';
        $properties = [
            'foo' => [
                Header::NAME => 'Foo',
                Header::DATA_TYPE => 'integer',
            ],
            'bar' => [
                Header::NAME => 'Bar',
                Header::DATA_TYPE => 'varchar',
            ],
        ];

        $render = new ResponseBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
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
    public function __construct(array $data)
    {
    }
}

T), $render->build());
    }
}
