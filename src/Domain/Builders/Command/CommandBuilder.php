<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Command;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class CommandBuilder extends AbstractBuilder
{
    public function __construct(string $entity, string $action, SchemaInterface $schema)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $action = $this->getPascalCase($action);
        $command = $this->getDashCase($this->getPluralize($entity)) . ':' . $this->getDashCase($action);

        $properties = [];

        if ($schema) {
            $properties = \array_reduce($schema->attributes(), function ($result, SchemaAttributeInterface $property) {
                $result[$property->name()] = $property;

                return $result;
            }, []);
        }

        parent::__construct(\compact('entity', 'action', 'properties', 'command'));
    }

    protected function getFileTemplate(): string
    {
        return 'Command.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Command', parent::getPathTemplate());
    }
}
