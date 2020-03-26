<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Repository;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

final class RepositoryBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $actions)
    {
        $actions = \array_reduce($actions, function (array $result, string $action) {
            $result[] = $this->getCamelCase($action);

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'actions'));
    }

    protected function getFileTemplate(): string
    {
        return 'Repository.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Repository', parent::getPathTemplate());
    }
}
