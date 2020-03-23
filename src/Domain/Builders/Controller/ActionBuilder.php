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
use Symfony\Component\HttpFoundation\Request;

class ActionBuilder extends AbstractBuilder
{
    public function __construct(
        string $entity,
        string $action,
        string $requestMessage = '',
        string $useCase = '',
        string $responseMessage = ''
    ) {
        $action = !empty($action)
            ? $this->getSnakeCase($action)
            : 'index';

        $data['action'] = $action;
        $data['entity'] = $entity;
        $data['request_message'] = $requestMessage;
        $data['use_case'] = $useCase;
        $data['response_message'] = $responseMessage;
        $data['action_camel'] = $this->getCamelCase($action);

        if (empty($data['route'])) {
            $data['route'] = $this->getGuessRoute($action);
        }

        if (empty($data['methods'])) {
            $data['methods'] = $this->getGuessMethod($action);
        }

        parent::__construct($data);
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
