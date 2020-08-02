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
use FlexPHP\Schema\SchemaInterface;

final class UseCaseBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, string $action)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $action = $this->getInflector()->pascalAction($action);
        $item = $this->getInflector()->item($schema->name());
        $pkName = $this->getInflector()->camelProperty($schema->pkName());

        parent::__construct(\compact('entity', 'action', 'item', 'pkName'));
    }

    public function build(): string
    {
        return \rtrim(parent::build());
    }

    protected function getFileTemplate(): string
    {
        return 'UseCase.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
