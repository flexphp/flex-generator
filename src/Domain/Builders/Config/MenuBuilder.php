<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Config;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

final class MenuBuilder extends AbstractBuilder
{
    public function __construct(array $entities)
    {
        $names = [];
        $icons = [];
        $roles = [];
        $routes = [];

        foreach ($entities as $entity => $icon) {
            $names[] = $this->getName($entity);
            $icons[] = $icon;
            $roles[] = $this->getRole($entity);
            $routes[] = $this->getRoute($entity);
        }

        parent::__construct(\compact('names', 'icons', 'roles', 'routes'));
    }

    protected function getFileTemplate(): string
    {
        return 'Menu.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Config', parent::getPathTemplate());
    }

    private function getName(string $entity): string
    {
        return $this->getDashCase($this->getSnakeCase($this->getSingularize($entity)));
    }

    private function getRole(string $entity): array
    {
        $name = \strtoupper($this->getSingularize($entity));

        return [
            'global' => \sprintf('ROLE_USER_%s_*', $name),
            'index' => \sprintf('ROLE_USER_%s_INDEX', $name),
            'create' => \sprintf('ROLE_USER_%s_CREATE', $name),
        ];
    }

    private function getRoute(string $entity): array
    {
        $name = $this->getPluralize($this->getDashCase($entity));

        return [
            'index' => \sprintf('%s.index', $name),
            'create' => \sprintf('%s.new', $name),
        ];
    }
}
