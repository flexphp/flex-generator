<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UploadedFile;
use Symfony\Component\Yaml\Yaml;

$request = Request::createFromGlobals();
$response = new Response();
$response->headers->set('Content-Type', 'application/json');
$response->setStatusCode(Response::HTTP_BAD_REQUEST);

$executionTime = \microtime(true);

try {
    /** @var UploadedFile $file */
    $file = $request->files->get('file', null);

    // die(var_dump($file));
    if ($file && $file->getError() === UPLOAD_ERR_OK) {
        // die(var_dump($file));
        $position = 0;
        $sheetNames = [];

        switch ($file->guessClientExtension()) {
            case 'xlsx':
                // MS Excel >= 2007
            case 'ods':
                // Open Format
                // die(var_dump($file->getRealPath()));
                $reader = ReaderEntityFactory::createReaderFromFile($file->getRealPath());
                $reader->open($file->getRealPath());

                foreach ($reader->getSheetIterator() as $sheet) {
                    if (!$sheet->isVisible()) {
                        continue;
                    }

                    $sheetName = trim($sheet->getName());
                    $sheetNames[] = $sheet->getName();

                    $headers = [];
                    $colHeaderName = 0;
                    $yaml = [];
                    $yaml[$sheetName] = [];

                    foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                        $rowNumber -= 1;
                        $cols = $row->getCells();

                        // die(var_dump($rowNumber));

                        foreach ($cols as $colNumber => $col) {
                            if ($rowNumber === 0) {
                                // echo $colNumber . PHP_EOL;
                                $headers[$colNumber] = $col->getValue();

                                if (strtolower($col->getValue() === 'name')) {
                                    $colHeaderName = $colNumber;
                                }

                                continue;
                            }

                            $fieldName = $cols[$colHeaderName]->getValue();

                            $yaml[$sheetName][$fieldName][$headers[$colNumber]] = $col->getValue();
                        }
                    }

                    file_put_contents(sprintf('%1$s/tmp/%2$s.yaml', __DIR__, strtolower($sheetName)), Yaml::dump($yaml));
                }

                $rpta['sheetNames'] = $sheetNames;

                $response->setStatusCode(Response::HTTP_OK);

                break;
            default:
                $rpta['message'] = 'Format no supported.';

                break;
        }
    }
} catch (Exception $e) {
    $rpta['message'] = sprintf('%1$s(%2$d): %3$s', $e->getFile(), $e->getLine(), $e->getMessage());
}

$rpta['executionTime'] = \microtime(true) - $executionTime;
// var_dump($rpta);
$response->setContent(\json_encode($rpta));

echo $response;
