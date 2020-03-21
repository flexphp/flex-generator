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

class GetterBuilder extends AbstractBuilder
{
    use TypeHintTrait;

    /**
     * @param array[] $data
     */
    public function __construct(array $data, array $config = [])
    {
        $name = (string)\array_key_first($data);
        $typehint = $this->guessTypeHint($data[$name]);
        $getter = $this->getPascalCase($name);

        $_data = \compact('name', 'typehint', 'getter');

        parent::__construct($_data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Getter.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    public function build(): string
    {
        return \rtrim(parent::build());
    }
}
