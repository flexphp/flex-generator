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

class GatewayBuilderTest extends TestCase
{
    public function testItDbOk(): void
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

        $render = new GatewayBuilder([
            'entity' => $entity,
            'actions' => $actions,
        ]);

        $this->assertEquals(\str_replace(
            "\r\n",
            "\n",
            <<<T
<?php

namespace Domain\Test\Gateway;

use Domain\Test\Repository\TestRepository;
use Doctrine\DBAL\Connection;

final class DBTestRepository implements TestRepository
{
    private \$conn;
    private \$query;
    private \$key = 'id';
    private \$lastId = null;

    public function __construct(Connection \$conn)
    {
        \$this->conn = \$conn;
        \$this->query = \$conn->createQueryBuilder();
    }

    public function index(array \$data): array
    {
        \$query->select('*');
        \$query->from('test');

        foreach(\$data as \$index => \$column) {
            \$query->where(\key(\$column) . ' = ?');
            \$query->setParameter(\$index, \$column);
        }

        return \$query->execute()->fetchAll();
    }

    public function create(array \$data): void
    {
        \$query->insert('test');

        foreach(\$data as \$index => \$column) {
            \$query->setValue(\key(\$column), '?');
            \$query->setParameter(\$index, \$column);
        }

        \$query->execute();

        \$this->lastId = \$conn->lastInsertId();
    }

    public function read(string \$id): ?array
    {
        \$query->select('*');
        \$query->from('test');
        \$query->where([\$this->key => \$id]);

        return \$query->execute()->fetch();
    }

    public function update(string \$id, array \$data): int
    {
        \$query->update('test');

        foreach(\$data as \$index => \$column) {
            \$query->setValue(\key(\$column), '?');
            \$query->setParameter(\$index, \$column);
        }

        return \$query->execute();
    }

    public function delete(string \$id): int
    {
        \$query->delete('test');
        \$query->where([\$this->key => \$id]);

        return \$query->execute();
    }

    public function customFuz(array \$data): array
    {
        return \$data;
    }
}

T
        ), $render->build());
    }
}
