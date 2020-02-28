<?php

namespace FlexPHP\Generator\Tests\Functional;

use FlexPHP\Generator\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GeneratorTest extends WebTestCase
{
    public function testItFileEmptyError()
    {
        $_FILES = [];

        \ob_start();
        include __DIR__ . '/../../src/dist/build.php';
        $response = \ob_get_clean();

        $this->assertContains('Upload file has error.', $response);
    }

    public function testItFileUploadError()
    {
        $name = 'Format.xlsx';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . '/src/dist/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type, UPLOAD_ERR_FORM_SIZE),
        ];

        \ob_start();
        include __DIR__ . '/../../src/dist/build.php';
        $response = \ob_get_clean();

        $this->assertContains('exceeds the upload limit', $response);
    }

    public function testItFileUploadExtensionError()
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

    public function testItFileFormatXlsx()
    {
        $name = 'Format.xlsx';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . '/src/dist/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type),
        ];

        \ob_start();
        include __DIR__ . '/../../src/dist/build.php';
        $response = \ob_get_clean();

        $sheetNames = \json_decode($response, true);

        foreach (array_keys($sheetNames) as $sheetName) {
            $yaml = \sprintf('%1$s/../../src/tmp/%2$s.yaml', __DIR__, \strtolower($sheetName));
            $this->assertFileExists($yaml);
            \unlink($yaml);
        }
    }
}
