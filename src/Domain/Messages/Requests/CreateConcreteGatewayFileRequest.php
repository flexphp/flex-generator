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
    public \FlexPHP\Schema\SchemaInterface $schema;

    public string $concrete;

    public array $actions;

    public function __construct(SchemaInterface $schema, string $concrete, array $actions)
    {
        $this->schema = $schema;
        $this->concrete = $concrete;
        $this->actions = $actions;
    }
}
