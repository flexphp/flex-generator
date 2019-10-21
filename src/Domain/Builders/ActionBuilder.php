<?php

namespace FlexPHP\Generator\Domain\Builders;

use Symfony\Component\HttpFoundation\Request;

class ActionBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        $data['action'] = $data['action'] ?? 'index';
        $action = strtolower($data['action']);

        if (empty($data['route'])) {
            $data['route'] = $this->getGuessRoute($action);
        }

        if (empty($data['methods'])) {
            $data['methods'] = $this->getGuessMethod($action);
        }

        parent::__construct($data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Action.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }

    private function getGuessMethod($action)
    {
        $method = Request::METHOD_GET;

        switch($action) {
            case 'create':
                $method = Request::METHOD_POST;
                break;
            case 'update':
                $method = Request::METHOD_PUT;
                break;
        }

        return $method;
    }

    private function getGuessRoute($action)
    {
        $route = '/' . $action;

        switch($action) {
            case 'index':
                $route = '/';
                break;
        }

        return $route;
    }
}
