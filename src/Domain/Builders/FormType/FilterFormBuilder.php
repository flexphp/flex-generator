<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\FormType;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\SchemaInterface;

final class FilterFormBuilder extends FormTypeBuilder
{
    protected function getFileTemplate(): string
    {
        return 'FilterForm.php.twig';
    }
}
