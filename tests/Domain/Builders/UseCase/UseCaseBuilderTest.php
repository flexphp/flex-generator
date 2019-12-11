<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\UseCase\UseCaseBuilder;
use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

class UseCaseBuilderTest extends TestCase
{
    public function testItRenderOk()
    {
        $action = 'action';
        $entity = 'Test';
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

        $render = new UseCaseBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<T
<?php

namespace Domain\Test\UseCase;

use Domain\Test\Message\ActionTestRequest;
use Domain\Test\Message\ActionTestResponse;
use FlexPHP\UseCases\UseCase;

/**
 * UseCase to action on Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class ActionTestUseCase extends UseCase
{
    private \$foo;
    private \$bar;

    public function execute(\$request): ActionTestResponse
    {
        \$this->foo = \$request->foo;
        \$this->bar = \$request->bar;

        return ActionTestResponse();
    }
}

T
), $render->build());
    }
}
