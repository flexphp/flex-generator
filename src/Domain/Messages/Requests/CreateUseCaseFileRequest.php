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

final class CreateUseCaseFileRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var string
     */
    public $action;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var string
     */
    public $outputFolder;

    public function __construct(string $entity, string $action, array $attributes, string $outputFolder)
    {
        $this->entity = $entity;
        $this->action = $action;
        $this->attributes = $attributes;
        $this->outputFolder = $outputFolder;
    }
}
