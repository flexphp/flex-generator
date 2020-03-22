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

use FlexPHP\Schema\Constants\Keyword;

trait TypeHintTrait
{
    protected function guessTypeHint(array $properties): string
    {
        $typeHint = 'string';
        $dataType = $properties[Keyword::DATATYPE] ?? 'string';

        switch ($dataType) {
            case 'smallint':
            case 'integer':
            case 'bigint':
            case 'decimal':
                $typeHint = 'int';

                break;
            case 'float':
            case 'double':
                $typeHint = 'float';

                break;
            case 'bool':
            case 'boolean':
                $typeHint = 'bool';

                break;
            case 'array':
            case 'simple_array':
            case 'json_array':
                $typeHint = 'array';

                break;
            // Only 7.2
            // case 'object':
                // $typeHint = 'object';
                // break;
        }

        return $typeHint;
    }
}
