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
use FlexPHP\Generator\Tests\TestCase;

final class ResponseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $action = 'action';
        $entity = 'Fuz';

        $render = new ResponseBuilder($entity, $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Message;

use FlexPHP\Messages\ResponseInterface;

final class ActionFuzResponse implements ResponseInterface
{
    public function __construct(array \$data)
    {
    }
}

T
, $render->build());
    }
}
