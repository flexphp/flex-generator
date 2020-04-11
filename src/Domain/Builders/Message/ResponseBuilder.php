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

final class ResponseBuilder extends AbstractBuilder
{
    public function __construct(string $entity, string $action)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $name = $this->getCamelCase($this->getSingularize($entity));
        $item = $this->getCamelCase($this->getPluralize($entity));
        $action = $this->getPascalCase($action);
        $key = 'id';

        parent::__construct(\compact('entity', 'name', 'item', 'action', 'key'));
    }

    protected function getFileTemplate(): string
    {
        return 'Response.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Message', parent::getPathTemplate());
    }
}
