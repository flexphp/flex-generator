<?php

namespace FlexPHP\Generator\Tests\Domain\Validations;

use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Domain\Validators\PropertyDataTypeValidator;
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
     * @dataProvider propertyNameNotValid
     */
    public function testItPropertyNameNotValidThrownException($name): void
    {
        $this->expectException(FieldSyntaxValidationException::class);

        $validation = new FieldSyntaxValidation([
            Keyword::NAME => $name,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyNameValid
     */
    public function testItPropertyNameOk($name): void
    {
        $validation = new FieldSyntaxValidation([
            Keyword::NAME => $name,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    /**
     * @dataProvider propertyDataTypeNotValid
     */
    public function testItPropertyDataTyoeNotValidThrownException($dataType): void
    {
        $this->expectException(FieldSyntaxValidationException::class);

        $validation = new FieldSyntaxValidation([
            Keyword::DATA_TYPE => $dataType,
        ]);

        $validation->validate();
    }

    /**
     * @dataProvider propertyDataTypeValid
     */
    public function testItPropertyDataTypeOk($dataType): void
    {
        $validation = new FieldSyntaxValidation([
            Keyword::DATA_TYPE => $dataType,
        ]);

        $validation->validate();

        $this->assertTrue(true);
    }

    public function propertyNameNotValid(): array
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

    public function propertyDataTypeNotValid(): array
    {
        return [
            ['unknow'],
            ['bool'],
            ['barchar'],
            ['interger'],
            ['int'],
            [null],
            [[]],
            [1],
        ];
    }

    public function propertyDataTypeValid(): array
    {
        return array_map(function($dataType) {
            return [$dataType];
        }, PropertyDataTypeValidator::ALLOWED_DATA_TYPES);
    }
}
