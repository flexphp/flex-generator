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
use FlexPHP\Schema\Schema;

final class RepositoryBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['index']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\IndexTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function findBy(IndexTestRequest \$request): array
    {
        return \$this->getGateway()->search((array)\$request, [], 10);
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['create']);

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

        \$this->getGateway()->push(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['read']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\ReadTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function getById(ReadTestRequest \$request): Test
    {
        \$factory = new TestFactory();
        \$data = \$this->getGateway()->get(\$factory->make(\$request));

        return \$factory->make(\$data);
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['update']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\UpdateTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function change(UpdateTestRequest \$request): void
    {
        \$test = (new TestFactory())->make(\$request);

        \$this->getGateway()->shift(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['delete']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\DeleteTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function remove(DeleteTestRequest \$request): void
    {
        \$test = (new TestFactory())->make(\$request);

        \$this->getGateway()->pop(\$test);
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new RepositoryBuilder(new Schema('Test', 'bar', []), ['login']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\LoginTestRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function getByLogin(LoginTestRequest \$request): Test
    {
        \$data = \$this->getGateway()->getBy('email', \$request->email);

        return (new TestFactory())->make(\$data);
    }
}

T
, $render->build());
    }

    public function testItRenderFkRelationsOk(): void
    {
        $render = new RepositoryBuilder($this->getSchemaFkRelation(), ['index']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\Request\FindTestBarRequest;
use Domain\Test\Request\FindTestPostRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function findBy(IndexTestRequest \$request): array
    {
        return \$this->getGateway()->search((array)\$request, [], 10);
    }

    public function findBarsByTerm(FindCommentBarRequest \$request): array
    {
        return \$this->getGateway()->filterBars(\$request->term, \$request->page, 10);
    }

    public function findPostsByTerm(FindCommentPostRequest \$request): array
    {
        return \$this->getGateway()->filterPosts(\$request->term, \$request->page, 10);
    }
}

T
, $render->build());
    }

    public function testItRenderBlameByOk(): void
    {
        $render = new RepositoryBuilder($this->getSchemaStringAndBlameBy(), ['index']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\Request\FindTestUserRequest;
use FlexPHP\Repositories\Repository;

final class TestRepository extends Repository
{
    public function findBy(IndexTestRequest \$request): array
    {
        return \$this->getGateway()->search((array)\$request, [], 10);
    }

    public function findUsersByTerm(FindCommentUserRequest \$request): array
    {
        return \$this->getGateway()->filterUsers(\$request->term, \$request->page, 10);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderWithDiffNameOk(string $entity, string $expected): void
    {
        $render = new RepositoryBuilder(new Schema($entity, 'bar', []), ['action']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

use Domain\\{$expected}\Request\Action{$expected}Request;
use FlexPHP\Repositories\Repository;

final class {$expected}Repository extends Repository
{
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
