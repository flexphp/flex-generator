<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

use Symfony\Component\HttpFoundation\Request;

class ActionBuilder extends ControllerBuilder
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
            case 'delete':
                $method = Request::METHOD_DELETE;
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
            case 'read':
                $route = '/{id}';
                break;
            case 'update':
            case 'delete':
                $route = \sprintf('/%1$s/{id}', $action);
                break;
        }

        return $route;
    }
}
