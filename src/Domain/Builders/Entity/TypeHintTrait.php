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

trait TypeHintTrait
{
    protected function guessTypeHint(string $dataType): string
    {
        $typeHintByDataType = [
            'smallint' => 'int',
            'integer' => 'int',
            'bigint' => 'int',
            'decimal' => 'int',
            'float' => 'float',
            'double' => 'float',
            'bool' => 'bool',
            'boolean' => 'bool',
            'array' => 'array',
            'simple_array' => 'array',
            'json_array' => 'array',
            // 'object' => 'object',
        ];

        if (isset($typeHintByDataType[$dataType])) {
            return $typeHintByDataType[$dataType];
        }

        return 'string';
    }
}
