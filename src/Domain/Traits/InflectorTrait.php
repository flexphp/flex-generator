<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Traits;

use Jawira\CaseConverter\Convert;
use Symfony\Component\Inflector\Inflector;

trait InflectorTrait
{
    protected function getPascalCase(string $string): string
    {
        return (new Convert($string))->toPascal();
    }

    protected function getCamelCase(string $string): string
    {
        return (new Convert($string))->toCamel();
    }

    protected function getSnakeCase(string $string): string
    {
        return (new Convert($string))->toSnake();
    }

    private function getSingularize(string $string): string
    {
        $singularize = Inflector::singularize($string);

        return \is_array($singularize)
            ? $singularize[0]
            : $singularize;
    }
}
