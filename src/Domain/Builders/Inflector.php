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

use Jawira\CaseConverter\Convert;
use Symfony\Component\String\Inflector\EnglishInflector;

final class Inflector
{
    public function table(string $string): string
    {
        return $string;
    }

    public function entity(string $string): string
    {
        return $this->pascal($this->singularize($string));
    }

    public function fnPlural(string $string): string
    {
        return $this->pluralize($this->pascal($string));
    }

    public function fnSingular(string $string): string
    {
        return $this->singularize($this->pascal($string));
    }

    public function item(string $string): string
    {
        return $this->singularize($this->camel($string));
    }

    public function items(string $string): string
    {
        return $this->pluralize($this->camel($string));
    }

    public function route(string $string): string
    {
        return $this->pluralize($this->dash($string));
    }

    public function routeName(string $name, string $action): string
    {
        return $this->pluralize($this->dash($name)) . '.' . $this->dash($action);
    }

    public function pascalProperty(string $string): string
    {
        return $this->pascal($string);
    }

    public function camelProperty(string $string): string
    {
        return $this->camel($string);
    }

    public function form(string $string): string
    {
        return $this->snake($this->singularize($string));
    }

    public function action(string $string): string
    {
        return $this->snake($string);
    }

    public function pascalAction(string $string): string
    {
        return $this->pascal($string);
    }

    public function camelAction(string $string): string
    {
        return $this->camel($string);
    }

    public function dashAction(string $string): string
    {
        return $this->dash($string);
    }

    public function role(string $string): string
    {
        return \strtoupper($this->singularize($this->camel($string)));
    }

    public function commandName(string $entity, string $action): string
    {
        return $this->pluralize($this->dash($entity)) . ':' . $this->dash($action);
    }

    public function entityTitleSingular(string $entity): string
    {
        return $this->singularize((new Convert($entity))->toTitle());
    }

    public function entityTitlePlural(string $entity): string
    {
        return $this->pluralize((new Convert($entity))->toTitle());
    }

    public function propertyTitle(string $name): string
    {
        return (new Convert($name))->toTitle();
    }

    public function dbName(string $string): string
    {
        return $this->snake($string);
    }

    public function jsName(string $string): string
    {
        return $this->pluralize($this->camel($string));
    }

    public function sheetName(string $string): string
    {
        return $this->pascal($string);
    }

    public function prototypeName(string $string): string
    {
        return $this->snake($string);
    }

    public function singular(string $string): string
    {
        return $this->singularize($string);
    }

    public function plural(string $string): string
    {
        return $this->pluralize($string);
    }

    private function pascal(string $string): string
    {
        return (new Convert($string))->toPascal();
    }

    private function camel(string $string): string
    {
        return (new Convert($string))->toCamel();
    }

    private function snake(string $string): string
    {
        return (new Convert($string))->toSnake();
    }

    private function dash(string $string): string
    {
        return (new Convert($string))->toKebab();
    }

    private function singularize(string $string): string
    {
        if (\substr(\strtolower($string), -3) === 'ice') {
            return $string;
        }

        if (\substr(\strtolower($string), -6) === 'status') {
            return $string;
        }

        $singularize = EnglishInflector::singularize($string);

        return \is_array($singularize)
            ? \array_pop($singularize)
            : $singularize;
    }

    private function pluralize(string $string): string
    {
        if (\substr(\strtolower($string), -3) === 'ice') {
            $onlyUpper = \preg_match('#^[A-Z]+$#', $string);

            return $string . ($onlyUpper ? 'S' : 's');
        }

        if (\substr(\strtolower($string), -6) === 'status') {
            return $string;
        }

        $pluralize = EnglishInflector::pluralize($this->singularize($string));

        return \is_array($pluralize)
            ? $pluralize[0]
            : $pluralize;
    }
}
