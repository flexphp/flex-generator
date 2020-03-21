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

class RepositoryBuilderTest extends TestCase
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

        $render = new RepositoryBuilder([
            'entity' => $entity,
            'actions' => $actions,
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
<?php

namespace Domain\Test\Repository;

use Domain\Test\Entity\Test;
use FlexPHP\Repositoty\RepositoryInterface;

/**
 * Repository used for Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
interface TestRepository extends RepositoryInterface
{
    public function index(array \$data): array;

    public function create(Test \$entity): array;

    public function read(string \$id): ?Test;

    public function update(Test \$entity): array;

    public function delete(Test \$entity): array;

    public function customFuz(array \$data): array;
}

T
        ), $render->build());
    }
}
