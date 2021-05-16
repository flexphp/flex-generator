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
use Exception;
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Exceptions\FormatPathNotValidException;
use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\Messages\Responses\ProcessFormatResponse;
use FlexPHP\Generator\Domain\Validations\FieldSyntaxValidation;
use FlexPHP\Generator\Domain\Validations\HeaderSyntaxValidation;
use FlexPHP\Generator\Domain\Writers\YamlWriter;
use FlexPHP\Schema\Constants\Keyword;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

final class ProcessFormatUseCase
{
    private const COLUMN_A = 0;

    private const COLUMN_B = 1;

    private ?int $rowHeaders = null;

    private \FlexPHP\Generator\Domain\Builders\Inflector $inflector;

    public function __construct()
    {
        $this->inflector = new Inflector();
    }

    /**
     * @throws FormatPathNotValidException
     * @throws FormatNotSupportedException
     */
    public function execute(ProcessFormatRequest $request): ProcessFormatResponse
    {
        $path = $request->path;

        if (!\is_file($path)) {
            throw new FormatPathNotValidException();
        }

        $yamls = [];
        $sheetNames = [];
        $outputDir = \sprintf('%1$s/../../../src/tmp', __DIR__);
        $outputTmp = \sprintf('%1$s/../../../src/tmp/skeleton', __DIR__);
        $name = \str_ireplace('.' . $request->extension, '', $request->filename);

        $reader = $this->getReader($request->extension);
        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($this->isIgnored($sheet)) {
                continue;
            }

            $sheetName = $this->inflector->sheetName($sheet->getName());
            $conf = $this->getConf($sheetName, $sheet);
            $headers = $this->getHeaders($sheet);
            $attributes = $this->getAttributes($sheet, $headers);
            $sheetNames[$sheetName] = \count($attributes);
            $yamls[$sheetName] = $this->createYaml($sheetName, $conf, $attributes, $outputTmp . '/yamls');
        }

        $this->createPrototype($this->inflector->prototypeName($name), $yamls, $outputTmp);

        $this->createZip($name, $outputTmp, $outputDir);

        return new ProcessFormatResponse($sheetNames);
    }

    private function isIgnored(SheetInterface $sheet): bool
    {
        return !$sheet->isVisible() || \substr($sheet->getName(), 0, 1) === '_';
    }

    private function getReader(?string $extension): ReaderInterface
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
                $header = \trim($col->getValue());

                if (empty($header)) {
                    continue;
                }

                $headers[$colNumber] = $header;
            }

            try {
                (new HeaderSyntaxValidation($headers))->validate();
            } catch (Exception $exception) {
                throw new Exception(\sprintf('Sheet [%s]: %s', $sheet->getName(), $exception->getMessage()));
            }

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
            $name = $this->inflector->camelProperty($properties[$colHeaderName]);
            $attributes[$name] = $properties;
        }

        return $attributes;
    }

    private function getProperties(array $cols, array $headers): array
    {
        $attributes = [];

        foreach ($cols as $colNumber => $col) {
            $value = \trim($col->getValue());

            if (empty($value)) {
                continue;
            }

            $attributes[$headers[$colNumber]] = $value;
        }

        (new FieldSyntaxValidation($attributes))->validate();

        return $attributes;
    }

    private function createYaml(string $sheetName, array $conf, array $attributes, string $output): string
    {
        $writer = new YamlWriter([
            $sheetName => $conf + [Keyword::ATTRIBUTES => $attributes],
        ], \strtolower($sheetName), $output);

        return $writer->save();
    }

    private function createPrototype(string $name, array $sheets, string $output): CreatePrototypeResponse
    {
        return (new CreatePrototypeUseCase())->execute(new CreatePrototypeRequest($name, $sheets, $output));
    }

    private function createZip(string $name, string $outputTmp, string $outputDir): string
    {
        $outputTmp = \realpath($outputTmp);
        $outputDir = \realpath($outputDir);

        $src = $outputTmp . \DIRECTORY_SEPARATOR . $name . '.zip';
        $dst = $outputDir . \DIRECTORY_SEPARATOR . $name . '.zip';

        $this->getZip($src, $outputTmp);

        \rename($src, $dst);

        $this->deleteFolder($outputTmp);

        return $dst;
    }

    private function getZip(string $name, string $path): void
    {
        $zip = new ZipArchive();
        $zip->open($name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $realPath = $file->getRealPath();
                $relativePath = \substr($realPath, \strlen($path) + 1);

                $zip->addFile($realPath, $relativePath);
            }
        }

        $zip->close();
    }

    private function deleteFolder(string $dir): void
    {
        if (\is_dir($dir)) {
            $objects = \array_diff(\scandir($dir), ['.', '..']);

            foreach ($objects as $object) {
                if (\is_dir($dir . '/' . $object) && !\is_link($dir . '/' . $object)) {
                    $this->deleteFolder($dir . '/' . $object);
                } else {
                    \unlink($dir . '/' . $object);
                }
            }

            \closedir(\opendir($dir));
            \rmdir($dir);
        }
    }
}
