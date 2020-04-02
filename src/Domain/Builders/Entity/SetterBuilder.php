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

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

final class SetterBuilder extends AbstractBuilder
{
    use TypeHintTrait;

    public function __construct(string $name, string $dataType)
    {
        $name = $this->getCamelCase($name);
        $setter = $this->getPascalCase($name);
        $typehint = $this->guessTypeHint($dataType);

        parent::__construct(\compact('name', 'typehint', 'setter'));
    }

    protected function getFileTemplate(): string
    {
        return 'Setter.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }
}
