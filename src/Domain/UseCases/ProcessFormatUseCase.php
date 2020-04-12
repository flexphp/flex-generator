<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\UseCases;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Exceptions\FormatPathNotValidException;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\Generator\Domain\Writers\YamlWriter;
use FlexPHP\Schema\Constants\Keyword;

final class ProcessFormatUseCase
{
    use InflectorTrait;

    /**
     * @throws FormatPathNotValidException
     * @throws FormatNotSupportedException
     */
    public function execute(ProcessFormatRequest $request): ProcessFormatResponse
    {
        $sheetNames = [];
        $path = $request->path;
        $extension = $request->extension;

        if (!\is_file($path)) {
            throw new FormatPathNotValidException();
        }

        switch ($extension) {
            case 'xlsx': // MS Excel >= 2007
                $reader = ReaderEntityFactory::createXLSXReader();

                break;
            // case 'ods': // Open Format
            //     $reader = ReaderEntityFactory::createODSReader();

            //     break;
            default:
                throw new FormatNotSupportedException();
        }

        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            if (!$sheet->isVisible()) {
                continue;
            }

            $sheetName = $this->getPascalCase($sheet->getName());

            $headers = [];
            $fields = [];

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

                $colHeaderName = $headers[\array_search(Keyword::NAME, $headers)];
                $fieldName = $this->getCamelCase($field[$colHeaderName]);
                $fields[$fieldName] = $field;
            }

            $writer = new YamlWriter([
                $sheetName => [
                    'Entity' => $sheetName,
                    'Attributes' => $fields,
                ],
            ], \strtolower($sheetName));

            $writer->save();

            $sheetNames[$sheetName] = \count($fields);
        }

        return new ProcessFormatResponse($sheetNames);
    }
}
