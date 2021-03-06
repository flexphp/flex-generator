<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;

final class RequestMessageBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, string $action)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $action = $this->getInflector()->pascalAction($action);
        $createdBy = '';
        $updatedBy = '';
        $hasFilter = $schema->hasAction(Action::FILTER);

        \array_filter(
            $schema->attributes(),
            // @phpstan-ignore-next-line
            function (SchemaAttributeInterface $property) use (&$createdBy, &$updatedBy): void {
                if ($property->isCb()) {
                    $createdBy = $this->getInflector()->camelProperty((string)$property->fkId());
                }

                if ($property->isUb()) {
                    $updatedBy = $this->getInflector()->camelProperty((string)$property->fkId());
                }
            }
        );

        parent::__construct(\compact('entity', 'item', 'action', 'createdBy', 'updatedBy', 'hasFilter'));
    }

    public function build(): string
    {
        return \rtrim(parent::build());
    }

    protected function getFileTemplate(): string
    {
        return 'RequestMessage.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/src/Controller', parent::getPathTemplate());
    }
}
