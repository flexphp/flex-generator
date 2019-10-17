<?php

namespace FlexPHP\Generator\Domain\UseCases;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\UseCases\UseCase;
use Symfony\Component\Yaml\Yaml;

class ProcessFormatUseCase extends UseCase
{
    /**
     * Process format based in extension
     * - Syntax
     *
     * @param ProcessFormatRequest $request
     * @return ProcessFormatResponse
     */
    public function execute($request)
    {
        $executionTime = \microtime(true);
        $path = $request->path;
        $extension = $request->extension;

        $response = [];
        $sheetNames = [];

        switch ($extension) {
            case 'xlsx':
                // MS Excel >= 2007
            case 'ods':
                // Open Format
                $reader = ReaderEntityFactory::createReaderFromFile($path);
                $reader->open($path);

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

                        foreach ($cols as $colNumber => $col) {
                            if ($rowNumber === 0) {
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

                    file_put_contents(sprintf('%1$s/../../tmp/%2$s.yaml', __DIR__, strtolower($sheetName)), Yaml::dump($yaml));
                }

                $response['sheetNames'] = $sheetNames;

                break;
            default:
                $response['error'] = true;
                $response['message'] = 'Format no supported.';

                break;
        }

        $response['executionTime'] = \microtime(true) - $executionTime;

        return new ProcessFormatResponse($response);
    }
}
