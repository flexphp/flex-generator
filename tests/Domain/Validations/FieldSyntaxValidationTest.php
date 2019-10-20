<?php

namespace FlexPHP\Generator\Tests\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;

class FieldSyntaxValidationTest extends TestCase
{
    public function testItPropertyUnknowThrownException(): void
    {
        $this->expectException(FieldSyntaxValidationException::class);
        $this->expectExceptionMessage('unknow');

        $validation = new FieldSyntaxValidation([
            'UnknowProperty' => 'Test',
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameInvalid
     */
    public function testItPropertyNameNotValidThrownException($name): void
    {
        $this->expectException(FieldSyntaxValidationException::class);

        $validation = new FieldSyntaxValidation([
            Header::NAME => $name,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameValid
     */
    public function testItPropertyNameOk($name): void
    {
        $validation = new FieldSyntaxValidation([
            Header::NAME => $name,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    public function propertyNameInvalid(): array
    {
        return [
            ['#Name'],
            ['1Name'],
            ['Name$'],
            [str_repeat('N', 65)],
            [''],
        ];
    }

    public function propertyNameValid(): array
    {
        return [
            ['Name'],
            ['N123'],
            ['Name_Test'],
            ['name_test'],
            ['_name'],
            [str_repeat('N', 64)],
            ['N'],
        ];
    }
}
