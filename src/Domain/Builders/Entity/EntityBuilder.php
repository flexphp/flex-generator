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
use FlexPHP\Schema\Constants\Keyword;

final class EntityBuilder extends AbstractBuilder
{
    public function __construct(string $name, array $properties)
    {
        $name = $this->getPascalCase($this->getSingularize($name));
        $getters = $this->getGetters($properties);
        $setters = $this->getSetters($properties);
        $_properties = $this->getProperties($properties);

        parent::__construct(\compact('name', 'getters', 'setters', '_properties'));
    }

    protected function getFileTemplate(): string
    {
        return 'Entity.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Entity', parent::getPathTemplate());
    }

    private function getProperties(array $properties): array
    {
        return \array_values(\array_reduce($properties, function (array $result, array $attributes): array {
            $result[] = $this->getCamelCase($attributes[Keyword::NAME]);

            return $result;
        }, []));
    }

    private function getGetters(array $properties): array
    {
        return \array_reduce($properties, function (array $result, array $attributes): array {
            $result[] = new GetterBuilder($attributes[Keyword::NAME], $attributes[Keyword::DATATYPE]);

            return $result;
        }, []);
    }

    private function getSetters(array $properties): array
    {
        return \array_reduce($properties, function (array $result, array $attributes): array {
            $result[] = new SetterBuilder($attributes[Keyword::NAME], $attributes[Keyword::DATATYPE]);

            return $result;
        }, []);
    }
}
