<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Factory;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Generator\Domain\Builders\Entity\TypeHintTrait;
use FlexPHP\Schema\Constants\Keyword;

final class FactoryBuilder extends AbstractBuilder
{
    use TypeHintTrait;

    public function __construct(string $entity, array $properties)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $item = $this->getCamelCase($this->getSingularize($entity));
        $setters = $this->getSetterWithCasting($properties);

        parent::__construct(\compact('entity', 'item', 'setters'));
    }

    protected function getFileTemplate(): string
    {
        return 'Factory.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Factory', parent::getPathTemplate());
    }

    private function getSetterWithCasting(array $properties): array
    {
        return \array_reduce($properties, function (array $result, array $attributes): array {
            $result[] = [
                'pascal' => $this->getPascalCase($attributes[Keyword::NAME]),
                'camel' => $this->getCamelCase($attributes[Keyword::NAME]),
                'typehint' => $this->guessTypeHint($attributes[Keyword::DATATYPE]),
            ];

            return $result;
        }, []);
    }
}
