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
use FlexPHP\Schema\SchemaAttributeInterface;

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

    protected function getPkName(array $properties): string
    {
        $pkName = 'id';

        \array_filter($properties, function (SchemaAttributeInterface $property) use (&$pkName): void {
            if ($property->isPk()) {
                $pkName = $property->name();
            }
        });

        return $pkName;
    }

    protected function getFkRelations(array $properties): array
    {
        $fkRelations = \array_reduce($properties, function (array $result, SchemaAttributeInterface $property): array {
            if ($property->isfk()) {
                $result[$property->name()] = [
                    'fnPlural' => $this->getPascalCase($this->getPluralize($property->fkTable())),
                    'fnSingular' => $this->getPascalCase($this->getSingularize($property->fkTable())),
                    'route' => $this->getDashCase($this->getPluralize($property->fkTable())),
                    'table' => $property->fkTable(),
                    'id' => $property->fkId(),
                    'text' => $property->fkName(),
                ];
            }

            return $result;
        }, []);

        return $fkRelations;
    }

    abstract protected function getFileTemplate(): string;
}
