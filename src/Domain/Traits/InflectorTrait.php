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

    protected function getDashCase(string $string): string
    {
        return (new Convert($string))->toKebab();
    }

    protected function getSingularize(string $string): string
    {
        if (\substr(\strtolower($string), -6) === 'status') {
            return $string;
        }

        $singularize = Inflector::singularize($string);

        return \is_array($singularize)
            ? \array_pop($singularize)
            : $singularize;
    }

    protected function getPluralize(string $string): string
    {
        if (\substr(\strtolower($string), -3) === 'ice') {
            $onlyUpper = \preg_match('@^[A-Z]+$@', $string);

            return $string . ($onlyUpper ? 'S' : 's');
        }

        if (\substr(\strtolower($string), -6) === 'status') {
            return $string;
        }

        $pluralize = Inflector::pluralize($this->getSingularize($string));

        return \is_array($pluralize)
            ? $pluralize[0]
            : $pluralize;
    }
}
