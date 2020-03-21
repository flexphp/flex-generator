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

use FlexPHP\Generator\Domain\Builders\Message\RequestBuilder;
use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

class RequestBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $action = 'action';
        $entity = 'Fuz';
        $properties = [
            'foo' => [
                Keyword::NAME => 'Foo',
                Keyword::DATA_TYPE => 'integer',
            ],
            'bar' => [
                Keyword::NAME => 'Bar',
                Keyword::DATA_TYPE => 'varchar',
            ],
        ];

        $render = new RequestBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
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
    public \$foo;
    public \$bar;

    public function __construct(array \$data)
    {
        \$this->foo = \$data['foo'] ?? null;
        \$this->bar = \$data['bar'] ?? null;
    }
}

T
), $render->build());
    }
}
