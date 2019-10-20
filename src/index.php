<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UploadedFile;

try {
    $request = Request::createFromGlobals();

    /** @var UploadedFile|null $file */
    $file = $request->files->get('file', null);

    if ($file && $file->getError() === UPLOAD_ERR_OK) {
        $useCase = new ProcessFormatUseCase();

        $response = $useCase->execute(
            new ProcessFormatRequest($file->getRealPath(), $file->guessClientExtension())
        );
    } else {
        $message = $file ? $file->getErrorMessage() : 'Upload file has error.';

        $response = new \stdClass();
        $response->messages = ['message' => $message];
        $response->hasError = true;
    }
} catch (Exception $e) {
    $response = new \stdClass();
    $response->messages = ['message' => sprintf('%1$s(%2$d): %3$s', $e->getFile(), $e->getLine(), $e->getMessage())];
    $response->hasError = true;
} finally {
    echo new Response(
        \json_encode($response->messages),
        (!$response->hasError ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST),
        [
            'Content-Type' => 'application/json',
        ]
    );
}
