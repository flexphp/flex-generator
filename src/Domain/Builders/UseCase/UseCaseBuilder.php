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
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class UseCaseBuilder extends AbstractBuilder
{
    private $action;

    public function __construct(SchemaInterface $schema, string $action)
    {
        $this->action = $this->getInflector()->camelAction($action);

        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $items = $this->getInflector()->items($schema->name());
        $action = $this->getInflector()->pascalAction($action);
        $properties = \array_reduce(
            $schema->attributes(),
            function ($result, SchemaAttributeInterface $property) {
                $result[$this->getInflector()->camelProperty($property->name())] = $property->properties();

                return $result;
            },
            []
        );

        parent::__construct(\compact('entity', 'item', 'items', 'action', 'properties'));
    }

    protected function getFileTemplate(): string
    {
        if (\in_array($this->action, ['index', 'create', 'read', 'update', 'delete', 'login'])) {
            return $this->action . '.php.twig';
        }

        return 'default.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/UseCase', parent::getPathTemplate());
    }
}
