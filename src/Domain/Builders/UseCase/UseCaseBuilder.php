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
use FlexPHP\Schema\Constants\Keyword;

final class UseCaseBuilder extends AbstractBuilder
{
    private $action;

    public function __construct(string $entity, string $action, array $properties)
    {
        $this->action = $this->getCamelCase($action);

        $entity = $this->getPascalCase($this->getSingularize($entity));
        $item = $this->getCamelCase($this->getPluralize($entity));
        $action = $this->getPascalCase($action);

        $properties = \array_reduce($properties, function ($result, $property) {
            $result[$this->getCamelCase($property[Keyword::NAME])] = $property;

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'item', 'action', 'properties'));
    }

    protected function getFileTemplate(): string
    {
        if (in_array($this->action, ['index', 'create'])) {
            return $this->action . '.php.twig';
        }

        return 'default.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }
}
