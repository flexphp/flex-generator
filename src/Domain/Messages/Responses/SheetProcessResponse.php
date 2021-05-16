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
    public ?string $controller = null;

    public ?string $entity = null;

    public ?string $gateway = null;

    public ?string $concreteGateway = null;

    public ?string $factory = null;

    public ?string $repository = null;

    public ?string $constraint = null;

    public ?string $translate = null;

    public ?string $formType = null;

    /**
     * @var array<int, string>
     */
    public array $requests = [];

    /**
     * @var array<int, string>
     */
    public array $responses = [];

    /**
     * @var array<int, string>
     */
    public array $useCases = [];

    /**
     * @var array<int, string>
     */
    public array $commands = [];

    /**
     * @var array<int, string>
     */
    public array $templates = [];

    public ?string $javascript = null;

    public function __construct(array $response)
    {
        $this->controller = $response['controller'] ?? null;
        $this->entity = $response['entity'] ?? null;
        $this->gateway = $response['gateway'] ?? null;
        $this->concreteGateway = $response['concreteGateway'] ?? null;
        $this->factory = $response['factory'] ?? null;
        $this->repository = $response['repository'] ?? null;
        $this->constraint = $response['constraint'] ?? null;
        $this->translate = $response['translate'] ?? null;
        $this->formType = $response['formType'] ?? null;
        $this->requests = $response['requests'] ?? [];
        $this->responses = $response['responses'] ?? [];
        $this->useCases = $response['useCases'] ?? [];
        $this->commands = $response['commands'] ?? [];
        $this->templates = $response['templates'] ?? [];
        $this->javascript = $response['javascript'] ?? null;
    }
}
