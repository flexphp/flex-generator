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

final class CreateTranslateFileRequest
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var array
     */
    public $language;

    public function __construct(string $entity, array $attributes, string $language)
    {
        $this->entity = $entity;
        $this->attributes = $attributes;
        $this->language = $language;
    }
}
