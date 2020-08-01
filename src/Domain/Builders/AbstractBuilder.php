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

use FlexPHP\Generator\Domain\Traits\InflectorTrait;

abstract class AbstractBuilder implements BuilderInterface
{
    use InflectorTrait;

    /**
     * @var array<array|string>
     */
    private $data;

    /**
     * @param array<array|string> $data
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

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/../BoilerPlates', __DIR__);
    }

    protected function getFkRelations(array $fkRelations): array
    {
        $fkRels = [];

        foreach ($fkRelations as $name => $fkRel) {
            $name = $this->getCamelCase($fkRel['pkId']);

            $fkRels[$name] = [
                'fnPlural' => $this->getPascalCase($this->getPluralize($fkRel['pkTable'])),
                'fnSingular' => $this->getPascalCase($this->getSingularize($fkRel['pkTable'])),
                'item' => $this->getCamelCase($this->getSingularize($fkRel['pkTable'])),
                'route' => $this->getDashCase($this->getPluralize($fkRel['pkTable'])),
                'table' => $this->getSnakeCase($this->getPluralize($fkRel['pkTable'])),
                'pk' => $fkRel['pkId'],
                'pkName' => $name,
                'dataType' => $fkRel['pkDataType'],
                'typeHint' => $fkRel['pkTypeHint'],
                'id' => $this->getCamelCase($fkRel['fkId']),
                'text' => $this->getCamelCase($fkRel['fkName']),
                'fkRoute' => $this->getDashCase($this->getPluralize($fkRel['fkTable'])),
            ];
        }

        return $fkRels;
    }

    protected function getFkFunctions(array $fkRelations): array
    {
        $fkFunctions = [];

        \array_map(function (array $fkRel) use (&$fkFunctions): void {
            $fkFunctions[$fkRel['fnSingular']] = $fkRel;
        }, $this->getFkRelations($fkRelations));

        return $fkFunctions;
    }

    abstract protected function getFileTemplate(): string;
}
