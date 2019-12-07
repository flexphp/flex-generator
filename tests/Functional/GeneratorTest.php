<?php

namespace FlexPHP\Generator\Tests\Functional;

use FlexPHP\Generator\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GeneratorTest extends WebTestCase
{
    public function testItFileError()
    {
        $_FILES = [];

        \ob_start();
        include __DIR__ . '/../../src/index.php';
        $response = $this->parseHttpResponse(\ob_get_clean());
        // dump($response->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItFileUploadError()
    {
        $name = 'Format.xlsx';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . '/src/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type, UPLOAD_ERR_FORM_SIZE),
        ];

        \ob_start();
        include __DIR__ . '/../../src/index.php';
        $response = $this->parseHttpResponse(\ob_get_clean());
        // dump($response->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItFileUploadExtensionError()
    {
        $name = 'Format.docx';
        $type = 'application/msword';
        $path = \getcwd() . '/src/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type),
        ];

        \ob_start();
        include __DIR__ . '/../../src/index.php';
        $response = $this->parseHttpResponse(\ob_get_clean());
        // dump($response->getContent());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItFileFormatXlsx()
    {
        $name = 'Format.xlsx';
        $type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $path = \getcwd() . '/src/templates/' . $name;

        $_FILES = [
            'file' => $this->getFileMock($name, $path, $type),
        ];

        \ob_start();
        include __DIR__ . '/../../src/index.php';
        $response = $this->parseHttpResponse(\ob_get_clean());
        // dump($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $sheetNames = \json_decode($response->getContent(), true);

        foreach (array_keys($sheetNames) as $sheetName) {
            $yaml = \sprintf('%1$s/../../src/tmp/%2$s.yaml', __DIR__, \strtolower($sheetName));
            $this->assertFileExists($yaml);
            \unlink($yaml);
        }
    }
}
