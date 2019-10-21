<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Message;

use FlexPHP\Generator\Domain\Builders\Message\RequestBuilder;
use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Tests\TestCase;

class RequestBuilderTest extends TestCase
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

        $render = new RequestBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<'T'
<?php

namespace Domain\Fuz\Message;

use FlexPHP\Messages\RequestInterface;

/**
 * Request to action on Fuz.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class ActionFuzRequest implements RequestInterface
{
    public $foo;
    public $bar;

    public function __construct(array $data)
    {
        $this->foo = $data['foo'] ?? null;
        $this->bar = $data['bar'] ?? null;
    }
}

T), $render->build());
    }
}
