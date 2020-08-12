<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Gateway;

use FlexPHP\Generator\Domain\Builders\Gateway\GatewayBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class GatewayBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new GatewayBuilder($this->getSchemaFkRelation(), ['index', 'create', 'read', 'update', 'other', 'delete', 'login']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

use Domain\Test\Request\FindTestBarRequest;
use Domain\Test\Request\FindTestPostRequest;
use Domain\Test\Request\FindTestUserStatusRequest;

interface TestGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit): array;

    public function push(Test $test): int;

    public function get(Test $test): array;

    public function shift(Test $test): void;

    public function pop(Test $test): void;

    public function getBy(string $column, $value): array;

    public function filterBars(FindTestBarRequest $request, int $page, int $limit): array;

    public function filterPosts(FindTestPostRequest $request, int $page, int $limit): array;

    public function filterUserStatus(FindTestUserStatusRequest $request, int $page, int $limit): array;
}

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['index']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit): array;
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['create']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function push(Test $test): string;
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['read']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function get(Test $test): array;
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['update']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function shift(Test $test): void;
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['delete']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function pop(Test $test): void;
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['login']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function getBy(string $column, $value): array;
}

T
, $render->build());
    }

    public function testItRenderRelationsOk(): void
    {
        $render = new GatewayBuilder($this->getSchemaFkRelation('PostComments'), ['other']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\PostComment;

use Domain\PostComment\Request\FindPostCommentBarRequest;
use Domain\PostComment\Request\FindPostCommentPostRequest;
use Domain\PostComment\Request\FindPostCommentUserStatusRequest;

interface PostCommentGateway
{
    public function filterBars(FindPostCommentBarRequest $request, int $page, int $limit): array;

    public function filterPosts(FindPostCommentPostRequest $request, int $page, int $limit): array;

    public function filterUserStatus(FindPostCommentUserStatusRequest $request, int $page, int $limit): array;
}

T
, $render->build());
    }

    public function testItRenderBlameByOk(): void
    {
        $render = new GatewayBuilder($this->getSchemaStringAndBlameBy(), ['other']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOkManyActions(): void
    {
        $render = new GatewayBuilder($this->getSchema(), ['create', 'other']);

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\Test;

interface TestGateway
{
    public function push(Test $test): string;
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderOkWithDiffNameEntity(string $entity, string $expected, string $expectedSingular): void
    {
        $render = new GatewayBuilder($this->getSchema($entity), ['create']);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

interface {$expected}Gateway
{
    public function push({$expected} \${$expectedSingular}): string;
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
