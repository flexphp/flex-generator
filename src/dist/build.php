<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

try {
    $request = Request::createFromGlobals();

    $response = new \stdClass();
    $response->messages = ['message' => 'Unknow error'];
    $response->hasError = true;

    /** @var UploadedFile|null $file */
    $file = $request->files->get('file', null);

    if (php_sapi_name() !== 'cli' && !$request->isXmlHttpRequest()) {
        header('Location: index.html');
        die;
    } elseif (!$file || $file->getError() !== UPLOAD_ERR_OK) {
        $error = $file ? $file->getErrorMessage() : 'Upload file has error.';
        $response->messages = ['message' => $error];
    } else {
        $response = (new ProcessFormatUseCase())->execute(
            new ProcessFormatRequest($file->getRealPath(), $file->guessClientExtension())
        );
    }
} catch (Exception $e) {
    $response = new \stdClass();
    $response->messages = ['message' => sprintf('%1$s(%2$d): %3$s', $e->getFile(), $e->getLine(), $e->getMessage())];
    $response->hasError = true;
} finally {
    $content = \json_encode($response->messages);
    $status = $response->hasError 
        ? Response::HTTP_BAD_REQUEST
        : Response::HTTP_OK;

    (new Response($content, $status, [
            'Content-Type' => 'application/json',
    ]))->send();
}
