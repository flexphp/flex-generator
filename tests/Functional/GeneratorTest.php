<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Functional;

use FlexPHP\Generator\Tests\WebTestCase;

final class GeneratorTest extends WebTestCase
{
    public function testItFileEmptyError(): void
    {
        $_FILES = [];

        \ob_start();

        include __DIR__ . '/../../src/dist/build.php';

        $response = \ob_get_clean();

        $this->assertContains('Upload file has error.', $response);
    }

    public function testItFileUploadError(): void
    {
        $name = 'Format.xlsx';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . '/src/dist/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type, \UPLOAD_ERR_FORM_SIZE),
        ];

        \ob_start();

        include __DIR__ . '/../../src/dist/build.php';

        $response = \ob_get_clean();

        $this->assertContains('exceeds the upload limit', $response);
    }

    public function testItFileUploadExtensionError(): void
    {
        $name = 'Format.docx';
        $type = 'application/msword';
        $path = \getcwd() . '/src/dist/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type),
        ];

        \ob_start();

        include __DIR__ . '/../../src/dist/build.php';

        $response = \ob_get_clean();

        $this->assertContains('isn\'t supported', $response);
    }

    public function testItFileFormatXlsx(): void
    {
        $name = 'Format';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . "/src/dist/templates/{$name}.xlsx";

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type),
        ];

        \ob_start();

        include __DIR__ . '/../../src/dist/build.php';

        $this->assertEquals('{"Posts":6,"Comments":5,"Actions":2}', \ob_get_clean());

        $zip = \sprintf('%1$s/../../src/tmp/%2$s.zip', __DIR__, $name);

        $this->assertFileExists($zip);

        \unlink($zip);
    }
}
