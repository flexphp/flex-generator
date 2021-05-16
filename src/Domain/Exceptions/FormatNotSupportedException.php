<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Exceptions;

final class FormatNotSupportedException extends DomainException
{
    /**
     * @var string
     */
    protected $message = "Format isn't supported";
}
