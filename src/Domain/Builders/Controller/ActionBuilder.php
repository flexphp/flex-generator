<?php

namespace FlexPHP\Generator\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use Symfony\Component\HttpFoundation\Request;

class ActionBuilder extends AbstractBuilder
{
    /**
     * @param array<string> $data
     * @param array[] $config
     */
    public function __construct(array $data, array $config = [])
    {
        $action = $data['action'] = !empty($data['action']) && is_string($data['action'])
            ? $this->getSnakeCase($data['action'])
            : 'index';

        $data['action_camel'] = $this->getCamelCase($action);

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

    private function getGuessMethod(string $action): string
    {
        switch ($action) {
            case 'index':
            case 'read':
                $method = Request::METHOD_GET;
                break;
            case 'update':
                $method = Request::METHOD_PUT;
                break;
            case 'delete':
                $method = Request::METHOD_DELETE;
                break;
            case 'create':
            default:
                $method = Request::METHOD_POST;
                break;
        }

        return $method;
    }

    private function getGuessRoute(string $action): string
    {
        $route = '/' . $action;

        switch ($action) {
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
