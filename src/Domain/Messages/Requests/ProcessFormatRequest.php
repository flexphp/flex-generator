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

final class ProcessFormatRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var null|string
     */
    public $extension;

    public function __construct(string $path, ?string $extension)
    {
        $this->path = $path;
        $this->extension = $extension;
    }
}
