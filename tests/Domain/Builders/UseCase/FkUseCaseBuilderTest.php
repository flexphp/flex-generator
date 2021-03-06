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
    public function testItRenderOk(
        string $pkEntity,
        string $fkEntity,
        string $namespace,
        string $expected,
        string $items,
        string $item,
        string $entity
    ): void {
        $render = new FkUseCaseBuilder($pkEntity, $fkEntity);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$namespace}\UseCase;

use Domain\\{$namespace}\\{$namespace}Repository;
use Domain\\{$namespace}\Request\Find{$expected}Request;
use Domain\\{$namespace}\Response\Find{$expected}Response;

final class Find{$expected}UseCase
{
    private {$namespace}Repository \${$item}Repository;

    public function __construct({$namespace}Repository \${$item}Repository)
    {
        \$this->{$item}Repository = \${$item}Repository;
    }

    public function execute(Find{$expected}Request \$request): Find{$expected}Response
    {
        \${$items} = \$this->{$item}Repository->find{$entity}By(\$request);

        return new Find{$expected}Response(\${$items});
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['fuz', 'bar', 'Fuz', 'FuzBar', 'bars', 'fuz', 'Bars'],
            ['FUZ', 'BAR', 'Fuz', 'FuzBar', 'bars', 'fuz', 'Bars'],
            ['User', 'UserPassword', 'User', 'UserUserPassword', 'userPasswords', 'user', 'UserPasswords'],
            ['user', 'userPassword', 'User', 'UserUserPassword', 'userPasswords', 'user', 'UserPasswords'],
            ['user', 'user_password', 'User', 'UserUserPassword', 'userPasswords', 'user', 'UserPasswords'],
            ['user', 'user-password', 'User', 'UserUserPassword', 'userPasswords', 'user', 'UserPasswords'],
            ['Posts', 'Comments', 'Post', 'PostComment', 'comments', 'post', 'Comments'],
        ];
    }
}
