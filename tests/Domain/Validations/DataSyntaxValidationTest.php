<?php

namespace FlexPHP\Generator\Tests\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Domain\Exceptions\DataSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\DataSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;

class DataSyntaxValidationTest extends TestCase
{
    public function testItHeadersUnknowThrowException()
    {
        $this->expectException(DataSyntaxValidationException::class);
        $this->expectExceptionMessage('Unknow');

        $validation = new DataSyntaxValidation([
            Header::NAME,
            Header::DATA_TYPE,
            'UnknowHeader',
        ]);

        $validation->validate();
    }

    public function testItHeadersRequiredIncompleteThrowException()
    {
        $this->expectException(DataSyntaxValidationException::class);
        $this->expectExceptionMessage('Required');

        $validation = new DataSyntaxValidation([
            Header::NAME,
        ]);

        $validation->validate();
    }
}
