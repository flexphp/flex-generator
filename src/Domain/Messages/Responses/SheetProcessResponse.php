<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Messages\Responses;

final class SheetProcessResponse
{
    /**
     * @var null|string
     */
    public $controller;

    /**
     * @var null|string
     */
    public $entity;

    /**
     * @var null|string
     */
    public $gateway;

    /**
     * @var null|string
     */
    public $concreteGateway;

    /**
     * @var null|string
     */
    public $factory;

    /**
     * @var null|string
     */
    public $constraint;

    /**
     * @var array<int, string>
     */
    public $requests;

    /**
     * @var array<int, string>
     */
    public $responses;

    /**
     * @var array<int, string>
     */
    public $useCases;

    /**
     * @var array<int, string>
     */
    public $commands;

    /**
     * @var array<int, string>
     */
    public $templates;

    public function __construct(array $response)
    {
        $this->controller = $response['controller'] ?? null;
        $this->entity = $response['entity'] ?? null;
        $this->gateway = $response['gateway'] ?? null;
        $this->concreteGateway = $response['concreteGateway'] ?? null;
        $this->factory = $response['factory'] ?? null;
        $this->constraint = $response['constraint'] ?? null;
        $this->requests = $response['requests'] ?? [];
        $this->responses = $response['responses'] ?? [];
        $this->useCases = $response['useCases'] ?? [];
        $this->commands = $response['commands'] ?? [];
        $this->templates = $response['templates'] ?? [];
    }
}
