<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Messages\Responses;

final class CreateTemplateFileResponse
{
    /**
     * @var array
     */
    public $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }
}
