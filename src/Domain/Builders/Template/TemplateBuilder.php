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
use Jawira\CaseConverter\Convert;

final class TemplateBuilder extends AbstractBuilder
{
    private $action;

    public function __construct(string $entity, string $action, array $properties)
    {
        $this->action = $action;

        $entity = $this->getPascalCase($this->getPluralize($entity));
        $route = $this->getDashCase($entity);
        $headers = \array_reduce($properties, function ($result, $property) {
            $result[] = (new Convert($property[Keyword::NAME]))->toTitle();

            return $result;
        }, []);

        $properties = \array_reduce($properties, function ($result, $property) {
            $result[$property[Keyword::NAME]] = $property;

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'route', 'headers', 'properties'));
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
