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

use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Constants\Keyword;

final class HeaderSyntaxValidationTest extends TestCase
{
    public function testItHeadersUnknowThrowException(): void
    {
        $this->expectException(HeaderSyntaxValidationException::class);
        $this->expectExceptionMessage('Unknow');

        $validation = new HeaderSyntaxValidation([
            Keyword::NAME,
            Keyword::DATATYPE,
            'UnknowHeader',
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider getHeadersInvalid
     */
    public function testItHeadersRequiredIncompleteThrowException(array $headers): void
    {
        $this->expectException(HeaderSyntaxValidationException::class);
        $this->expectExceptionMessage('Required');

        $validation = new HeaderSyntaxValidation($headers);

        $validation->validate();
    }

    /**
     * @dataProvider getHeadersValid
     */
    public function testItHeadersOk(array $headers): void
    {
        $validation = new HeaderSyntaxValidation($headers);

        $validation->validate();

        $this->assertTrue(true);
    }

    public function getHeadersInvalid(): array
    {
        return [
            [[]],
            [[Keyword::NAME]],
            [[Keyword::NAME, Keyword::CONSTRAINTS]],
        ];
    }

    public function getHeadersValid(): array
    {
        return [
            [[Keyword::NAME, Keyword::DATATYPE]],
            [[Keyword::NAME, Keyword::DATATYPE, Keyword::CONSTRAINTS]],
            [[Keyword::CONSTRAINTS, Keyword::NAME, Keyword::DATATYPE]],
            [[Keyword::DATATYPE, Keyword::CONSTRAINTS, Keyword::NAME]],
        ];
    }
}
