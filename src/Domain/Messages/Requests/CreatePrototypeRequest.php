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

final class CreatePrototypeRequest implements RequestInterface
{
    /**
     * @var array
     */
    public $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }
}
