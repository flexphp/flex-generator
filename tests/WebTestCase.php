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
}
