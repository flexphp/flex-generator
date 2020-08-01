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
use FlexPHP\Schema\Schema;

final class FactoryBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new FactoryBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

final class TestFactory
{
    public function make(\$data)
    {
        \$test = new Test();

        if (is_array(\$data)) {
            \$data = (object)\$data;
        }

        if (isset(\$data->lower)) {
            \$test->setLower((string)\$data->lower);
        }
        if (isset(\$data->upper)) {
            \$test->setUpper((int)\$data->upper);
        }
        if (isset(\$data->pascalCase)) {
            \$test->setPascalCase(is_string(\$data->pascalCase) ? new \DateTime(\$data->pascalCase) : \$data->pascalCase);
        }
        if (isset(\$data->camelCase)) {
            \$test->setCamelCase((bool)\$data->camelCase);
        }
        if (isset(\$data->snakeCase)) {
            \$test->setSnakeCase((string)\$data->snakeCase);
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
        $render = new FactoryBuilder(new Schema($entity, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

final class {$expected}Factory
{
    public function make(\$data)
    {
        \${$item} = new {$expected}();

        if (is_array(\$data)) {
            \$data = (object)\$data;
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
            ['Posts', 'Post', 'post'],
        ];
    }
}
