<?php

namespace FlexPHP\Generator\Domain\UseCases;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use FlexPHP\Generator\Domain\Constants\Header;
use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\UseCases\UseCase;
use Symfony\Component\Yaml\Yaml;

class ProcessFormatUseCase extends UseCase
{
    /**
     * Process format based in extension
     * - Syntax Sheet
     *
     * @param ProcessFormatRequest $request
     * @return ProcessFormatResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, ProcessFormatRequest::class, $request);

        $sheetNames = [];
        $path = $request->path;
        $extension = $request->extension;

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

                    foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                        $rowNumber -= 1;
                        $cols = $row->getCells();
                        
                        if ($rowNumber === 0) {
                            foreach ($cols as $colNumber => $col) {
                                $headers[$colNumber] = $col->getValue();
                            }

                            $headerValidation = new HeaderSyntaxValidation($headers);
                            $headerValidation->validate();

                            continue;
                        }

                        $field = [];

                        foreach ($cols as $colNumber => $col) {
                            $field[$headers[$colNumber]] = $col->getValue();
                        }
                        
                        $fieldValidation = new FieldSyntaxValidation($field);
                        $fieldValidation->validate();
                    }

                    // file_put_contents(sprintf('%1$s/../../tmp/%2$s.yaml', __DIR__, strtolower($sheetName)), Yaml::dump($fields));
                }

                break;
            default:
                throw new FormatNotSupportedException();
                break;
        }

        return new ProcessFormatResponse($sheetNames);
    }
}
