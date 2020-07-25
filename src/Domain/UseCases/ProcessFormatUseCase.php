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
use Box\Spout\Reader\ReaderInterface;
use Box\Spout\Reader\SheetInterface;
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

    private const ROW_HEADERS = 'ROW_HEADERS';

    private const COLUMN_A = 0;

    private const COLUMN_B = 1;

    private $rowHeaders;

    /**
     * @throws FormatPathNotValidException
     * @throws FormatNotSupportedException
     */
    public function execute(ProcessFormatRequest $request): ProcessFormatResponse
    {
        $sheetNames = [];
        $path = $request->path;

        if (!\is_file($path)) {
            throw new FormatPathNotValidException();
        }

        $reader = $this->getReader($request->extension);
        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            if (!$sheet->isVisible()) {
                continue;
            }

            $sheetName = $this->getPascalCase($sheet->getName());
            $conf = $this->getConf($sheetName, $sheet);
            $headers = $this->getHeaders($sheet);
            $attributes = $this->getAttributes($sheet, $headers);

            $this->createFile($sheetName, $conf, $attributes);

            $sheetNames[$sheetName] = \count($attributes);
        }

        return new ProcessFormatResponse($sheetNames);
    }

    private function getReader(string $extension): ReaderInterface
    {
        switch ($extension) {
            case 'xlsx': // MS Excel >= 2007
                return ReaderEntityFactory::createXLSXReader();
            // case 'ods': // Open Format
            //     return ReaderEntityFactory::createODSReader();
            default:
                throw new FormatNotSupportedException();
        }
    }

    private function getConf(string $sheetName, SheetInterface $sheet): array
    {
        $this->rowHeaders = 1;

        $conf = [
            Keyword::TITLE => $sheetName,
            Keyword::ICON => '',
        ];

        foreach ($sheet->getRowIterator() as $rowNumber => $row) {
            $this->rowHeaders = $rowNumber;

            if ($row->getCellAtIndex(self::COLUMN_A)->getValue() === Keyword::NAME) {
                break;
            }

            $conf[$row->getCellAtIndex(self::COLUMN_A)->getValue()] = $row->getCellAtIndex(self::COLUMN_B)->getValue();
        }

        return $conf;
    }

    private function getHeaders(SheetInterface $sheet): array
    {
        $headers = [];

        foreach ($sheet->getRowIterator() as $rowNumber => $row) {
            if ($rowNumber < $this->rowHeaders) {
                continue;
            }

            $cols = $row->getCells();

            foreach ($cols as $colNumber => $col) {
                $headers[$colNumber] = $col->getValue();
            }

            (new HeaderSyntaxValidation($headers))->validate();

            break;
        }

        return $headers;
    }

    private function getAttributes(SheetInterface $sheet, array $headers): array
    {
        $attributes = [];

        foreach ($sheet->getRowIterator() as $rowNumber => $row) {
            if ($rowNumber <= $this->rowHeaders) {
                continue;
            }

            $cols = $row->getCells();

            $properties = $this->getProperties($cols, $headers);

            $colHeaderName = $headers[\array_search(Keyword::NAME, $headers)];
            $name = $this->getCamelCase($properties[$colHeaderName]);
            $attributes[$name] = $properties;
        }

        return $attributes;
    }

    private function getProperties(array $cols, array $headers): array
    {
        $attributes = [];

        foreach ($cols as $colNumber => $col) {
            $attributes[$headers[$colNumber]] = $col->getValue();
        }

        (new FieldSyntaxValidation($attributes))->validate();

        return $attributes;
    }

    private function createFile(string $sheetName, array $conf, array $attributes): void
    {
        $writer = new YamlWriter([
            $sheetName => $conf + [Keyword::ATTRIBUTES => $attributes],
        ], \strtolower($sheetName));

        $writer->save();
    }
}
