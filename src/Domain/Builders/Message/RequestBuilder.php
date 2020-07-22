<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Message;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class RequestBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, string $action)
    {
        $login = 'email';
        $entity = $this->getPascalCase($this->getSingularize($schema->name()));
        $name = $this->getCamelCase($this->getSingularize($entity));
        $action = $this->getPascalCase($action);
        $pkName = $this->getCamelCase($schema->pkName());
        $pkTypeHint = $schema->pkTypeHint();
        $properties = \array_reduce($schema->attributes(), function ($result, SchemaAttributeInterface $property) {
            $result[$this->getCamelCase($property->name())] = $property;

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'name', 'action', 'pkName', 'pkTypeHint', 'login', 'properties'));
    }

    protected function getFileTemplate(): string
    {
        return 'Request.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Message', parent::getPathTemplate());
    }
}
