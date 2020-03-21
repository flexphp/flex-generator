<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class RuleBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $property = \array_key_first($data);
        $rules = $data[$property];

        $_data = \compact('property', 'rules');

        parent::__construct($_data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Rule.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Constraint', parent::getPathTemplate());
    }

    public function build(): string
    {
        return \rtrim(parent::build());
    }
}
