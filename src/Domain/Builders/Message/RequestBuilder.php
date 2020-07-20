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
use FlexPHP\Generator\Domain\Builders\Entity\TypeHintTrait;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class RequestBuilder extends AbstractBuilder
{
    use TypeHintTrait;

    public function __construct(string $entity, string $action, ?SchemaInterface $schema = null)
    {
        $login = 'email';
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $name = $this->getCamelCase($this->getSingularize($entity));
        $action = $this->getPascalCase($action);

        $pkName = 'id';
        $pkTypeHint = 'string';
        $properties = [];

        if ($schema) {
            $pkName = $this->getPkName($schema->attributes());
            $pkTypeHint = $this->getPkTypeHint($schema->attributes());
            $properties = \array_reduce($schema->attributes(), function ($result, SchemaAttributeInterface $property) {
                $result[$this->getCamelCase($property->name())] = $property;

                return $result;
            }, []);
        }

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

    private function getPkTypeHint(array $properties): string
    {
        $pkTypeHint = 'string';

        \array_filter($properties, function (SchemaAttributeInterface $property) use (&$pkTypeHint): void {
            if ($property->isPk()) {
                $pkTypeHint = $this->guessTypeHint($property->dataType());
            }
        });

        return $pkTypeHint;
    }
}
