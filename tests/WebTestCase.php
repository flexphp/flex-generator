<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class WebTestCase
 */
class WebTestCase extends TestCase
{
    protected function getFileMock(string $name, string $path, string $type, $uploadError = \UPLOAD_ERR_OK): UploadedFile
    {
        return new UploadedFile($path, $name, $type, $uploadError, true);
    }
}
