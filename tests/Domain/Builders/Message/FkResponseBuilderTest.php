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

use FlexPHP\Generator\Domain\Builders\Message\FkResponseBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FkResponseBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new FkResponseBuilder('Fuz', 'Bar');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Fuz\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindFuzBarResponse implements ResponseInterface
{
    public \$bars;

    public function __construct(array \$bars)
    {
        \$this->bars = \$bars;
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOkWithDiffEntityName(
        string $pkEntity,
        string $fkEntity,
        string $namespace,
        string $expected,
        string $item
    ): void {
        $render = new FkResponseBuilder($pkEntity, $fkEntity);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$namespace}\Response;

use FlexPHP\Messages\ResponseInterface;

final class Find{$expected}Response implements ResponseInterface
{
    public \${$item};

    public function __construct(array \${$item})
    {
        \$this->{$item} = \${$item};
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['fuz', 'bar', 'Fuz', 'FuzBar', 'bars'],
            ['FUZ', 'BAR', 'Fuz', 'FuzBar', 'bars'],
            ['User', 'UserPassword', 'User', 'UserUserPassword', 'userPasswords'],
            ['user', 'userPassword', 'User', 'UserUserPassword', 'userPasswords'],
            ['user', 'user_password', 'User', 'UserUserPassword', 'userPasswords'],
            ['user', 'user-password', 'User', 'UserUserPassword', 'userPasswords'],
            ['Posts', 'Comments', 'Post', 'PostComment', 'comments'],
        ];
    }
}
