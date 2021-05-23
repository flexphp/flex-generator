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

use FlexPHP\Generator\Domain\Builders\Message\FkRequestBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FkRequestBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new FkRequestBuilder('Fuz', 'Bar');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Fuz\Request;

use FlexPHP\Messages\RequestInterface;

final class FindFuzBarRequest implements RequestInterface
{
    public \$term;

    public \$_page;

    public \$_limit;

    public function __construct(array \$data)
    {
        \$this->term = \$data['term'] ?? '';
        \$this->_page = \$data['page'] ?? 1;
        \$this->_limit = \$data['limit'] ?? 20;
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
        string $expected
    ): void {
        $render = new FkRequestBuilder($pkEntity, $fkEntity);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$namespace}\Request;

use FlexPHP\Messages\RequestInterface;

final class Find{$expected}Request implements RequestInterface
{
    public \$term;

    public \$_page;

    public \$_limit;

    public function __construct(array \$data)
    {
        \$this->term = \$data['term'] ?? '';
        \$this->_page = \$data['page'] ?? 1;
        \$this->_limit = \$data['limit'] ?? 20;
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['fuz', 'bar', 'Fuz', 'FuzBar'],
            ['FUZ', 'BAR', 'Fuz', 'FuzBar'],
            ['User', 'UserPassword', 'User', 'UserUserPassword'],
            ['user', 'userPassword', 'User', 'UserUserPassword'],
            ['user', 'user_password', 'User', 'UserUserPassword'],
            ['user', 'user-password', 'User', 'UserUserPassword'],
            ['Posts', 'Comments', 'Post', 'PostComment'],
        ];
    }
}
