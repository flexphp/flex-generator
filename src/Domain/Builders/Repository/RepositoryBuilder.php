<?php

namespace FlexPHP\Generator\Domain\Builders\Repository;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class RepositoryBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['actions']) && is_array($data['actions'])) {
            foreach ($data['actions'] as $index => $action) {
                $data['actions'][$index] = $this->getCamelCase($action);
            }
        }

        parent::__construct($data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Repository.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Repository', parent::getPathTemplate());
    }
}
