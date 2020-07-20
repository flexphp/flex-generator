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

final class CreateConcreteGatewayFileRequest
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var string
     */
    public $concrete;

    /**
     * @var array
     */
    public $actions;

    /**
     * @var SchemaInterface|null
     */
    public $schema;

    public function __construct(string $entity, string $concrete, array $actions, SchemaInterface $schema)
    {
        $this->entity = $entity;
        $this->concrete = $concrete;
        $this->actions = $actions;
        $this->schema = $schema;
    }
}
