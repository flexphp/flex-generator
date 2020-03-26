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

use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Tests\TestCase;

final class FieldSyntaxValidationTest extends TestCase
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
}
