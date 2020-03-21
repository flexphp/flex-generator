<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\UseCase\UseCaseBuilder;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

class UseCaseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $action = 'action';
        $entity = 'Test';
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

        $render = new UseCaseBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
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

    /**
     * @param ActionTestRequest \$request
     * @return ActionTestResponse
     */
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
