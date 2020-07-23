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

final class FkResponseBuilder extends AbstractBuilder
{
    public function __construct(string $pkEntity, string $fkEntity)
    {
        $pkEntity = $this->getPascalCase($this->getSingularize($pkEntity));
        $fkEntity = $this->getPascalCase($this->getSingularize($fkEntity));
        $item = $this->getCamelCase($this->getPluralize($fkEntity));

        parent::__construct(\compact('pkEntity', 'fkEntity', 'item'));
    }

    protected function getFileTemplate(): string
    {
        return 'FkResponse.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Message', parent::getPathTemplate());
    }
}
