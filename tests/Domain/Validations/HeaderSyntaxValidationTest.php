<?php

namespace FlexPHP\Generator\Tests\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;

class HeaderSyntaxValidationTest extends TestCase
{
    public function testItHeadersUnknowThrowException()
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

    public function testItHeadersRequiredIncompleteThrowException()
    {
        $this->expectException(HeaderSyntaxValidationException::class);
        $this->expectExceptionMessage('Required');

        $validation = new HeaderSyntaxValidation([
            Keyword::NAME,
        ]);

        $validation->validate();
    }
}
