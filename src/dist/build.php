<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$response = new \stdClass();
$response->messages = ['message' => 'Unknow error'];
$response->hasError = true;

try {
    $request = Request::createFromGlobals();

    /** @var null|UploadedFile $file */
    $file = $request->files->get('file', null);

    // @codeCoverageIgnoreStart
    if (PHP_SAPI !== 'cli' && !$request->isXmlHttpRequest()) {
        \header('Location: index.html');
        die;
    }
    // @codeCoverageIgnoreEnd

    if (!$file || $file->getError() !== \UPLOAD_ERR_OK) {
        $error = $file !== null ? $file->getErrorMessage() : 'Upload file has error.';
        $response->messages = ['message' => $error];
    } else {
        $response = (new ProcessFormatUseCase())->execute(
            new ProcessFormatRequest(
                (string)$file->getRealPath(),
                $file->getClientOriginalName(),
                $file->guessClientExtension()
            )
        );
    }
} catch (Exception $exception) {
    $response = new \stdClass();
    $response->messages = [
        'message' => \sprintf(
            "Error processing %s file:\n\n%s",
            $file->getClientOriginalName(),
            $exception->getMessage()
        ),
    ];
    $response->hasError = true;

    $logfile = \sprintf('%s/../../log/build_%s.log', __DIR__, \date('Y_m_d'));
    $log = \sprintf(
        "[%s]\t%s\t%s\t%s\n%s\n",
        \date('Y-m-d H:i:s'),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getMessage(),
        $exception->getTraceAsString()
    );

    \file_put_contents($logfile, $log, \FILE_APPEND);
} finally {
    $content = (string)\json_encode($response->messages);
    $status = $response->hasError
        ? Response::HTTP_BAD_REQUEST
        : Response::HTTP_OK;

    (new Response($content, $status, [
        'Content-Type' => 'application/json',
    ]))->send();
}
