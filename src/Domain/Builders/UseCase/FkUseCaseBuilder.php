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

final class FkUseCaseBuilder extends AbstractBuilder
{
    public function __construct(string $pkEntity, string $fkEntity)
    {
        $pkEntity = $this->getPascalCase($this->getSingularize($pkEntity));
        $fkEntity = $this->getPascalCase($this->getSingularize($fkEntity));
        $name = $this->getPluralize($pkEntity);
        $item = $this->getCamelCase($this->getPluralize($pkEntity));

        parent::__construct(\compact('pkEntity', 'fkEntity', 'name', 'item'));
    }

    protected function getFileTemplate(): string
    {
        return 'FkUseCase.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }
}