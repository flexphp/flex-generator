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
        return \array_reduce($fkRelations, function (array $result, array $fkRel): array {
            $result[] = [
                'fnPlural' => $this->getPascalCase($this->getPluralize($fkRel['table'])),
                'fnSingular' => $this->getPascalCase($this->getSingularize($fkRel['table'])),
                'route' => $this->getDashCase($this->getPluralize($fkRel['table'])),
                'table' => $fkRel['table'],
                'id' => $fkRel['id'],
                'text' => $fkRel['name'],
            ];

            return $result;
        }, []);
    }

    abstract protected function getFileTemplate(): string;
}
