<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Template;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\Constants\Keyword;

final class TemplateBuilder extends AbstractBuilder
{
    private $action;

    public function __construct(string $entity, string $action, array $properties)
    {
        $this->action = $action;

        $route = $this->getPluralize($this->getDashCase($entity));
        $item = $this->getCamelCase($this->getSingularize($entity));

        $properties = \array_reduce($properties, function (array $result, array $property) {
            $result[$this->getCamelCase($property[Keyword::NAME])] = $property;

            return $result;
        }, []);

        parent::__construct(\compact('route', 'item', 'properties'));
    }

    protected function getFileTemplate(): string
    {
        return $this->action . '.html.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/templates', parent::getPathTemplate());
    }
}
