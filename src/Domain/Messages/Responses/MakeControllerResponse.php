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

use FlexPHP\Messages\ResponseInterface;

class MakeControllerResponse implements ResponseInterface
{
    /**
     * @var string
     */
    public $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }
}
