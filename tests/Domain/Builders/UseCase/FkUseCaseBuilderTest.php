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

use FlexPHP\Generator\Domain\Builders\UseCase\FkUseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FkUseCaseBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOk(string $pkEntity, string $fkEntity, string $namespace, string $expected, string $item, string $entity): void
    {
        $render = new FkUseCaseBuilder($pkEntity, $fkEntity);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$namespace}\UseCase;

use Domain\\{$namespace}\Request\Find{$expected}Request;
use Domain\\{$namespace}\Response\Find{$expected}Response;
use FlexPHP\UseCases\UseCase;

final class Find{$expected}UseCase extends UseCase
{
    /**
     * @param Find{$expected}Request \$request
     *
     * @return Find{$expected}Response
     */
    public function execute(\$request)
    {
        \${$item} = \$this->getRepository()->find{$entity}ByTerm(\$request);

        return new Find{$expected}Response(\${$item});
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['fuz', 'bar', 'Fuz', 'FuzBar', 'fuzes', 'Fuzes'],
            ['FUZ', 'BAR', 'Fuz', 'FuzBar', 'fuzes', 'Fuzes'],
            ['User', 'UserPassword', 'User', 'UserUserPassword', 'users', 'Users'],
            ['user', 'userPassword', 'User', 'UserUserPassword', 'users', 'Users'],
            ['user', 'user_password', 'User', 'UserUserPassword', 'users', 'Users'],
            ['user', 'user-password', 'User', 'UserUserPassword', 'users', 'Users'],
            ['Posts', 'Comments', 'Post', 'PostComment', 'posts', 'Posts'],
        ];
    }
}
