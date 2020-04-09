<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Entity;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/types.html
 */
trait TypeHintTrait
{
    private function guessTypeHint(string $dataType): string
    {
        $typeHintByDataType = [
            'smallint' => 'int',
            'integer' => 'int',
            'float' => 'float',
            'double' => 'float',
            'bool' => 'bool',
            'boolean' => 'bool',
            'date' => '\DateTime',
            'date_inmutable' => '\DateTimeImmutable',
            'datetime' => '\DateTime',
            'datetime_inmutable' => '\DateTimeImmutable',
            'time' => '\DateTime',
            'time_inmutable' => '\DateTimeImmutable',
            'array' => 'array',
            'simple_array' => 'array',
            'json_array' => 'array',
        ];

        if (isset($typeHintByDataType[$dataType])) {
            return $typeHintByDataType[$dataType];
        }

        return 'string';
    }
}
