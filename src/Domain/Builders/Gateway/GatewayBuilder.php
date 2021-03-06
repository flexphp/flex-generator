<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Gateway;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaInterface;

final class GatewayBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, array $actions)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $typeHint = $schema->pkTypeHint();
        $actions = \array_reduce($actions, function (array $result, string $action) {
            $result[] = $this->getInflector()->camelAction($action);

            return $result;
        }, []);
        $header = self::getHeaderFile();

        parent::__construct(\compact('header', 'entity', 'actions', 'item', 'fkFns', 'typeHint'));
    }

    protected function getFileTemplate(): string
    {
        return 'Gateway.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Gateway', parent::getPathTemplate());
    }
}
