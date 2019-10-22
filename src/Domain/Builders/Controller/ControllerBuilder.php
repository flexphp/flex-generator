<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class ControllerBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        foreach ($data['actions'] as $action => $builder) {
            unset($data['actions'][$action]);
            $data['actions'][$this->getPascalCase($action)] = $builder;
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
