<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Repository;

use FlexPHP\Generator\Domain\Builders\Repository\RepositoryBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class RepositoryBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new RepositoryBuilder('Test', [
            'index',
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\IndexTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function findBy(IndexPostRequest \$request): array
    {
        return \$this->getGateway()->find((array)\$request, [], 10);
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new RepositoryBuilder('Test', [
            'create',
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\CreateTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function add(CreateTestRequest \$request): void
    {
        \$test = (new TestFactory())->make(\$request);

        \$this->getGateway()->persist(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new RepositoryBuilder('Test', [
            'read',
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\ReadTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function getById(ReadTestRequest \$request): array
    {
        return \$this->getGateway()->get(\$request->id);
    }
}

T
, $render->build());
    }
}
