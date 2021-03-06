<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var array<array|bool|string|mixed>
     */
    private array $data = [];

    public static function getHeaderFile(): string
    {
        return <<<H
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
H;
    }

    /**
     * @param array<array|bool|string|mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->build();
    }

    public function build(): string
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->getPathTemplate());
        $twig = new \Twig\Environment($loader);

        return $twig->render($this->getFileTemplate(), $this->data);
    }

    protected function getInflector(): Inflector
    {
        static $inflector;

        if ($inflector) {
            return $inflector;
        }

        return $inflector = new Inflector();
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/../BoilerPlates', __DIR__);
    }

    protected function getFkRelations(array $fkRelations): array
    {
        $fkRels = [];

        foreach ($fkRelations as $name => $fkRel) {
            $name = $this->getInflector()->camelProperty($fkRel['pkId']);

            $fkRels[$name] = [
                'fnPlural' => $this->getInflector()->fnPlural($fkRel['pkTable']),
                'fnSingular' => $this->getInflector()->fnSingular($fkRel['pkTable']),
                'item' => $this->getInflector()->item($fkRel['pkTable']),
                'items' => $this->getInflector()->items($fkRel['pkTable']),
                'route' => $this->getInflector()->route($fkRel['pkTable']),
                'table' => $fkRel['pkTable'],
                'pk' => $fkRel['pkId'],
                'pkName' => $name,
                'dataType' => $fkRel['pkDataType'],
                'typeHint' => $fkRel['pkTypeHint'],
                'id' => $this->getInflector()->camelProperty($fkRel['fkId']),
                'text' => $this->getInflector()->camelProperty($fkRel['fkName']),
                'fkRoute' => $this->getInflector()->route($fkRel['fkTable']),
                'fkItem' => $this->getInflector()->item($fkRel['fkTable']),
                'required' => $fkRel['isRequired'],
                'blameBy' => $fkRel['isBlameBy'],
                'chars' => \is_null($fkRel['minChars']) ? 3 : $fkRel['minChars'],
                'check' => $fkRel['check'],
                'pkNamePascal' => $this->getInflector()->pascalProperty($fkRel['pkId']),
            ];
        }

        return $fkRels;
    }

    protected function getFkFunctions(array $fkRelations): array
    {
        $fkFunctions = [];

        \array_map(function (array $fkRel) use (&$fkFunctions): void {
            $name = $this->getInflector()->camelProperty($fkRel['fnSingular']);

            $fkFunctions[$name] = $fkRel;
        }, $this->getFkRelations($fkRelations));

        return $fkFunctions;
    }

    abstract protected function getFileTemplate(): string;
}
