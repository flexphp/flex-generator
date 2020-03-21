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

class ControllerBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['actions']) && \is_array($data['actions'])) {
            foreach ($data['actions'] as $action => $builder) {
                unset($data['actions'][$action]);
                $data['actions'][$this->getPascalCase($action)] = $builder;
            }
        }

        parent::__construct($data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Controller.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }
}
