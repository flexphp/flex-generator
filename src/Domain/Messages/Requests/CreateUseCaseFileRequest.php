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
    public $properties;

    /**
     * @var string
     */
    public $outputFolder;

    public function __construct(string $entity, string $action, array $properties, string $outputFolder)
    {
        $this->entity = $entity;
        $this->action = $action;
        $this->properties = $properties;
        $this->outputFolder = $outputFolder;
    }
}
