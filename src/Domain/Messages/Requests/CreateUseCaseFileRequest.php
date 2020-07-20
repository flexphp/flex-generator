<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Messages\Requests;

use FlexPHP\Schema\SchemaInterface;

final class CreateUseCaseFileRequest
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var array
     */
    public $actions;

    /**
     * @var SchemaInterface
     */
    public $schema;

    public function __construct(string $entity, array $actions, SchemaInterface $schema)
    {
        $this->entity = $entity;
        $this->actions = $actions;
        $this->schema = $schema;
    }
}
