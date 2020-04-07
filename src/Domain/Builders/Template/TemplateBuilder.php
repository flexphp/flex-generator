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
use FlexPHP\Inputs\Builder\InputBuilder;
use FlexPHP\Schema\Constants\Keyword;
use Jawira\CaseConverter\Convert;

final class TemplateBuilder extends AbstractBuilder
{
    private $action;

    public function __construct(string $entity, string $action, array $properties)
    {
        $this->action = $action;

        $name = $this->getPascalCase($this->getSingularize($entity));
        $entity = $this->getPascalCase($this->getPluralize($entity));
        $route = $this->getDashCase($entity);
        $item = $this->getCamelCase($this->getSingularize($entity));
        $headers = \array_reduce($properties, function (array $result, array $property) {
            $result[] = (new Convert($property[Keyword::NAME]))->toTitle();

            return $result;
        }, []);

        $properties = \array_reduce($properties, function (array $result, array $property) {
            $result[$property[Keyword::NAME]] = $property;

            return $result;
        }, []);

        $inputs = \array_reduce($properties, function (array $result, array $property) {
            $options = \array_filter([
                'label' => (new Convert($property[Keyword::NAME]))->toSentence(),
                'required' => $property[Keyword::CONSTRAINTS]['required'] ?? null,
                'type' => $property[Keyword::CONSTRAINTS]['type'] ?? null,
            ]);

            $result[] = (new InputBuilder($this->getCamelCase($property[Keyword::NAME]), $options))->render();

            return $result;
        }, []);

        parent::__construct(\compact('name', 'entity', 'route', 'item', 'headers', 'properties', 'inputs'));
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
