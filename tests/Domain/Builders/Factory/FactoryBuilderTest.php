<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Factory;

use FlexPHP\Generator\Domain\Builders\Factory\FactoryBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FactoryBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {        
        $render = new FactoryBuilder('Test');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

final class TestFactory
{
    public function make(\$data)
    {
        \$test = new Test();

        foreach ((array)\$data as \$property => \$value) {
            \$setter = 'set' . \$property;
            \$test->{\$setter}(\$value);
        }

        return \$test;
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expected, string $item): void
    {
        $render = new FactoryBuilder($entity);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

final class {$expected}Factory
{
    public function make(\$data)
    {
        \${$item} = new {$expected}();

        foreach ((array)\$data as \$property => \$value) {
            \$setter = 'set' . \$property;
            \${$item}->{\$setter}(\$value);
        }

        return \${$item};
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword', 'userpassword'],
            ['USERPASSWORD', 'Userpassword', 'userpassword'],
            ['UserPassword', 'UserPassword', 'userPassword'],
            ['userPassword', 'UserPassword', 'userPassword'],
            ['user_password', 'UserPassword', 'userPassword'],
            ['user-password', 'UserPassword', 'userPassword'],
            ['Posts', 'Post', 'post'],
        ];
    }
}
