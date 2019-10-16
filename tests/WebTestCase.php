<?php

namespace FlexPHP\Generator\Tests;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebTestCase
 * @package FlexPHP\Generator\Tests
 */
class WebTestCase extends TestCase
{
    protected function getFileMock(string $name, string $path, string $type, $uploadError = UPLOAD_ERR_OK): array
    {
        return [
            'name' => $name,
            'type' => $type,
            'tmp_name' => $path,
            'error' => $uploadError,
            'size' => \strlen(\file_get_contents($path)),
        ];
    }

    protected function parseHttpResponse(string $httpResponse): Response
    {
        $content = '';
        $statusCode = Response::HTTP_OK;
        $headers = [];

        if (\preg_match('/^HTTP/', $httpResponse) > 0) {
            $lines = \preg_split('/\n/', $httpResponse);
            $count = \count($lines);
            
            $status = explode(' ', $lines[0]);
            $statusCode = $status[1];
            $content = $lines[$count - 1];
            unset($lines[0], $lines[($count - 1)], $lines[$count - 2]);

            foreach ($lines as $header) {
                list($name, $value) = \preg_split('/:/', $header, 2);
                $headers[$name] = $value;
            }
        }

        return new Response($content, $statusCode, $headers);
    }
}
