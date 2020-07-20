<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\CreateCommandFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateConcreteGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateConstraintFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateControllerFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateFactoryFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateFormTypeFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateRepositoryFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateRequestFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateResponseFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateTemplateFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateTranslateFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateUseCaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateCommandFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConcreteGatewayFileResponse as ConcreteGatewayFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConstraintFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateControllerFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateFactoryFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateFormTypeFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateGatewayFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRepositoryFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateResponseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTemplateFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTranslateFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateUseCaseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaInterface;

final class SheetProcessUseCase
{
    public function execute(SheetProcessRequest $request): SheetProcessResponse
    {
        $name = $request->name;
        $schema = Schema::fromFile($request->path);
        $actions = [
            'index',
            'create',
            'read',
            'update',
            'delete',
        ];

        if ($name === 'Users') {
            $actions = \array_merge($actions, ['login']);
        }

        $controller = $this->createController($name, $actions);
        $entity = $this->createEntity($name, $schema);
        $gateway = $this->createGateway($name, $actions);
        $concreteGateway = $this->createConcreteGateway($name, $actions, $schema);
        $factory = $this->createFactory($name, $schema);
        $repository = $this->createRepository($name, $actions, $schema);
        $constraint = $this->createConstraint($name, $schema);
        $translate = $this->createTranslate($name, $schema);
        $formType = $this->createFormType($name, $schema);
        $requests = $this->createRequests($name, $actions, $schema);
        $responses = $this->createResponses($name, $actions);
        $useCases = $this->createUseCases($name, $actions, $schema);
        $commands = $this->createCommands($name, $actions, $schema);
        $templates = $this->createTemplates($name, $schema);

        return new SheetProcessResponse([
            'controller' => $controller->file,
            'entity' => $entity->file,
            'gateway' => $gateway->file,
            'concreteGateway' => $concreteGateway->file,
            'factory' => $factory->file,
            'repository' => $repository->file,
            'constraint' => $constraint->file,
            'translate' => $translate->file,
            'formType' => $formType->file,
            'requests' => $requests->files,
            'responses' => $responses->files,
            'useCases' => $useCases->files,
            'commands' => $commands->files,
            'templates' => $templates->files,
        ]);
    }

    private function createController(string $name, array $actions): CreateControllerFileResponse
    {
        return (new CreateControllerFileUseCase())->execute(
            new CreateControllerFileRequest($name, $actions)
        );
    }

    private function createConstraint(string $name, SchemaInterface $schema): CreateConstraintFileResponse
    {
        return (new CreateConstraintFileUseCase())->execute(
            new CreateConstraintFileRequest($name, $schema)
        );
    }

    private function createTranslate(string $name, SchemaInterface $schema): CreateTranslateFileResponse
    {
        return (new CreateTranslateFileUseCase())->execute(
            new CreateTranslateFileRequest($name, $schema, 'en')
        );
    }

    private function createFormType(string $name, SchemaInterface $schema): CreateFormTypeFileResponse
    {
        return (new CreateFormTypeFileUseCase())->execute(
            new CreateFormTypeFileRequest($name, $schema)
        );
    }

    private function createEntity(string $name, SchemaInterface $schema): CreateEntityFileResponse
    {
        return (new CreateEntityFileUseCase())->execute(
            new CreateEntityFileRequest($name, $schema)
        );
    }

    private function createGateway(string $name, array $actions): CreateGatewayFileResponse
    {
        return (new CreateGatewayFileUseCase())->execute(
            new CreateGatewayFileRequest($name, $actions)
        );
    }

    private function createConcreteGateway(
        string $name,
        array $actions,
        SchemaInterface $schema
    ): ConcreteGatewayFileResponse {
        return (new CreateConcreteGatewayFileUseCase())->execute(
            new CreateConcreteGatewayFileRequest($name, 'MySQL', $actions, $schema)
        );
    }

    private function createFactory(string $name, SchemaInterface $schema): CreateFactoryFileResponse
    {
        return (new CreateFactoryFileUseCase())->execute(
            new CreateFactoryFileRequest($name, $schema)
        );
    }

    private function createRepository(
        string $name,
        array $actions,
        SchemaInterface $schema
    ): CreateRepositoryFileResponse {
        return (new CreateRepositoryFileUseCase())->execute(
            new CreateRepositoryFileRequest($name, $actions, $schema)
        );
    }

    private function createUseCases(string $name, array $actions, SchemaInterface $schema): CreateUseCaseFileResponse
    {
        return (new CreateUseCaseFileUseCase())->execute(
            new CreateUseCaseFileRequest($name, $actions, $schema)
        );
    }

    private function createRequests(string $name, array $actions, SchemaInterface $schema): CreateRequestFileResponse
    {
        return (new CreateRequestFileUseCase())->execute(
            new CreateRequestFileRequest($name, $schema, $actions)
        );
    }

    private function createResponses(string $name, array $actions): CreateResponseFileResponse
    {
        return (new CreateResponseFileUseCase())->execute(
            new CreateResponseFileRequest($name, $actions)
        );
    }

    private function createCommands(string $name, array $actions, SchemaInterface $schema): CreateCommandFileResponse
    {
        return (new CreateCommandFileUseCase())->execute(
            new CreateCommandFileRequest($name, $actions, $schema)
        );
    }

    private function createTemplates(string $name, SchemaInterface $schema): CreateTemplateFileResponse
    {
        return (new CreateTemplateFileUseCase())->execute(
            new CreateTemplateFileRequest($name, $schema)
        );
    }
}
