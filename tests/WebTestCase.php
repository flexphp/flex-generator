<?php

namespace FlexPHP\Generator\Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class WebTestCase
 * @package FlexPHP\Generator\Tests
 */
class WebTestCase extends TestCase
{
    protected function getFileMock(string $name, string $path, string $type, $uploadError = UPLOAD_ERR_OK): UploadedFile
    {
        return new UploadedFile($path, $name, $type, $uploadError, true);
    }

    protected function parseHttpResponse(string $httpResponse): Response
    {
        $statusCode = Response::HTTP_OK;
        $headers = [];

        $httpResponse = \substr($httpResponse, \strpos($httpResponse, 'HTTP'));
        $extraContent = \substr($httpResponse, 0, \strpos($httpResponse, 'HTTP'));

        $lines = \preg_split('/\n/', $httpResponse);
        $count = \count($lines);

        $status = explode(' ', $lines[0]);
        $statusCode = (int)$status[1];
        $content = $lines[$count - 1];

        if (\strlen($extraContent) > 0) {
            $content .= "\r\n" . $extraContent;
        }

        unset($lines[0], $lines[($count - 1)], $lines[$count - 2]);

        foreach ($lines as $header) {
            list($name, $value) = \preg_split('/:/', $header, 2);
            $headers[$name] = $value;
        }

        // die(var_dump($content, $statusCode, $headers));
        return new Response($content, $statusCode, $headers);
    }
}
