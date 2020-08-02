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

final class ResponseMessageBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema, string $action)
    {
        $action = $this->getInflector()->camelAction($action);
        $item = $this->getInflector()->item($schema->name());
        $items = $this->getInflector()->items($schema->name());
        $templates = $item;
        $route = $this->getInflector()->route($schema->name());

        parent::__construct(\compact('route', 'action', 'item', 'items', 'templates'));
    }

    public function build(): string
    {
        return \rtrim(parent::build());
    }

    protected function getFileTemplate(): string
    {
        return 'ResponseMessage.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
