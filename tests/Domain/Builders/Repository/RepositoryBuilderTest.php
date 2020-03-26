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
    public function testItOk(): void
    {
        $entity = 'Test';
        $actions = [
            'index',
            'create',
            'read',
            'update',
            'delete',
            'custom Fuz',
        ];

        $render = new RepositoryBuilder($entity, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Repository;

use FlexPHP\Repositoty\RepositoryInterface;

interface TestRepository extends RepositoryInterface
{
    public function index(array \$data): array;

    public function create(array \$data): void;

    public function read(string \$id): ?array;

    public function update(string \$id, array \$data): int;

    public function delete(string \$id): int;

    public function customFuz(array \$data): array;
}

T, $render->build());
    }
}
