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
use FlexPHP\Schema\SchemaInterface;
use Symfony\Component\HttpFoundation\Request;

final class ActionBuilder extends AbstractBuilder
{
    private string $action;

    public function __construct(
        SchemaInterface $schema,
        string $action,
        string $requestMessage = '',
        string $useCase = '',
        string $responseMessage = ''
    ) {
        $nflector = $this->getInflector();
        $action = !empty($action)
            ? $nflector->action($action)
            : 'index';

        $this->action = $action;

        $data = [];
        $data['action'] = $action;
        $data['entity'] = $nflector->entity($schema->name());
        $data['entity_dash'] = $nflector->route($schema->name());
        $data['item'] = $nflector->item($schema->name());
        $data['pkName'] = $nflector->camelProperty($schema->pkName());
        $data['pkTypeHint'] = $schema->pkTypeHint();
        $data['request_message'] = $requestMessage;
        $data['use_case'] = $useCase;
        $data['response_message'] = $responseMessage;
        $data['action_camel'] = $nflector->camelAction($action);
        $data['route'] = $this->getGuessRoute($nflector->dashAction($action));
        $data['route_name'] = $nflector->routeName($schema->name(), $action);
        $data['methods'] = $this->getGuessMethod($action);

        parent::__construct($data);
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/src/Controller', parent::getPathTemplate());
    }

    protected function getFileTemplate(): string
    {
        if (\in_array($this->action, ['index', 'create', 'read', 'update', 'delete'])) {
            return $this->action . '.php.twig';
        }

        return 'default.php.twig';
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
