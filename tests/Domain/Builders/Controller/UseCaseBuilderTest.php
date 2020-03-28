<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class UseCaseBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOk(string $entity, string $expected): void
    {
        $render = new UseCaseBuilder($entity, 'index');

        $this->assertEquals(<<<T
        \$useCase = new Index{$expected}UseCase();
        \$response = \$useCase->execute(\$request);
T, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new UseCaseBuilder('Test', 'index');

        $this->assertEquals(<<<T
        \$useCase = new IndexTestUseCase();
        \$response = \$useCase->execute(\$request);
T, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
