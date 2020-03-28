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

final class ActionBuilder extends AbstractBuilder
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
        $data['route'] = $this->getGuessRoute($this->getDashCase($action));
        $data['route_name'] = $this->getPluralize($this->getDashCase($entity)) . '.' . $this->getDashCase($action);
        $data['methods'] = $this->getGuessMethod($action);

        parent::__construct($data);
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }

    protected function getFileTemplate(): string
    {
        return 'Action.php.twig';
    }

    private function getGuessMethod(string $action): string
    {
        $methodByAction = [
            'index' => Request::METHOD_GET,
            'read' => Request::METHOD_GET,
            'update' => Request::METHOD_PUT,
            'delete' => Request::METHOD_DELETE,
            'create' => Request::METHOD_POST,
        ];

        if (isset($methodByAction[$action])) {
            return $methodByAction[$action];
        }

        return Request::METHOD_POST;
    }

    private function getGuessRoute(string $action): string
    {
        $routeByMethod = [
            'index' => '/',
            'read' => '/{id}',
            'update' => \sprintf('/%1$s/{id}', $action),
            'delete' => \sprintf('/%1$s/{id}', $action),
        ];

        if (isset($routeByMethod[$action])) {
            return $routeByMethod[$action];
        }

        return '/' . $action;
    }
}
