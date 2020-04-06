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

use FlexPHP\Messages\RequestInterface;

final class CreateTemplateFileRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var array
     */
    public $attributes;

    public function __construct(string $entity, array $attributes)
    {
        $this->entity = $entity;
        $this->attributes = $attributes;
    }
}