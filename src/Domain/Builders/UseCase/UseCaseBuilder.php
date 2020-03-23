<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\UseCase;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class UseCaseBuilder extends AbstractBuilder
{
    public function __construct(string $entity, string $action, array $properties)
    {
        parent::__construct(\compact('entity', 'action', 'properties'));
    }

    public function getFileTemplate(): string
    {
        return 'UseCase.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }
}
