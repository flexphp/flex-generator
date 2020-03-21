<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;

class HeaderSyntaxValidationTest extends TestCase
{
    public function testItHeadersUnknowThrowException(): void
    {
        $this->expectException(HeaderSyntaxValidationException::class);
        $this->expectExceptionMessage('Unknow');

        $validation = new HeaderSyntaxValidation([
            Keyword::NAME,
            Keyword::DATA_TYPE,
            'UnknowHeader',
        ]);

        $validation->validate();
    }

    public function testItHeadersRequiredIncompleteThrowException(): void
    {
        $this->expectException(HeaderSyntaxValidationException::class);
        $this->expectExceptionMessage('Required');

        $validation = new HeaderSyntaxValidation([
            Keyword::NAME,
        ]);

        $validation->validate();
    }
}
