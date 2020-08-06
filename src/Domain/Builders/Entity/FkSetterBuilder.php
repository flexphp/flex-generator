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

final class FkSetterBuilder extends AbstractBuilder
{
    public function __construct(string $name, string $type, bool $required)
    {
        $name = $this->getInflector()->camelProperty($name);
        $setter = $this->getInflector()->pascalProperty($name);
        $type = $this->getInflector()->entity($type);
        $typeName = $this->getInflector()->camelProperty($type);

        parent::__construct(\compact('name', 'setter', 'type', 'typeName', 'required'));
    }

    protected function getFileTemplate(): string
    {
        return 'FkSetter.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }
}
