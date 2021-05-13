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
use FlexPHP\Generator\Domain\Messages\Requests\CreateJavascriptFileRequest;
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
use FlexPHP\Generator\Domain\Messages\Responses\CreateJavascriptFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRepositoryFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateResponseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTemplateFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTranslateFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateUseCaseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\Schema\Constants\Action;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaInterface;

final class SheetProcessUseCase
{
    public function execute(SheetProcessRequest $request): SheetProcessResponse
    {
        $name = $request->name;
        $schema = Schema::fromFile($request->path);
        $actions = [];
        $actions[] = $schema->hasAction(Action::INDEX) ? 'index' : null;
        $actions[] = $schema->hasAction(Action::CREATE) ? 'create' : null;
        $actions[] = $schema->hasAction(Action::READ) ? 'read' : null;
        $actions[] = $schema->hasAction(Action::UPDATE) ? 'update' : null;
        $actions[] = $schema->hasAction(Action::DELETE) ? 'delete' : null;
        $actions[] = $schema->hasAction(Action::FILTER) ? 'filter' : null;

        if ($name === 'Users') {
            $actions = \array_merge($actions, ['login']);
        }

        $actions = \array_values(\array_filter($actions));

        $controller = $this->createController($schema, $actions);
        $entity = $this->createEntity($schema);
        $gateway = $this->createGateway($schema, $actions);
        $concreteGateway = $this->createConcreteGateway($schema, $actions);
        $factory = $this->createFactory($schema);
        $repository = $this->createRepository($schema, $actions);
        $constraint = $this->createConstraint($schema);
        $translate = $this->createTranslate($schema);
        $formType = $this->createFormType($schema);
        $requests = $this->createRequests($schema, $actions);
        $responses = $this->createResponses($schema, $actions);
        $useCases = $this->createUseCases($schema, $actions);
        $commands = $this->createCommands($schema, $actions);
        $templates = $this->createTemplates($schema);
        $javascript = null;

        if (!empty($schema->fkRelations())) {
            $javascript = $this->createJavascript($schema);
        }

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
            'javascript' => $javascript->file ?? null,
        ]);
    }

    private function createController(SchemaInterface $schema, array $actions): CreateControllerFileResponse
    {
        return (new CreateControllerFileUseCase())->execute(
            new CreateControllerFileRequest($schema, $actions)
        );
    }

    private function createConstraint(SchemaInterface $schema): CreateConstraintFileResponse
    {
        return (new CreateConstraintFileUseCase())->execute(
            new CreateConstraintFileRequest($schema)
        );
    }

    private function createTranslate(SchemaInterface $schema): CreateTranslateFileResponse
    {
        return (new CreateTranslateFileUseCase())->execute(
            new CreateTranslateFileRequest($schema)
        );
    }

    private function createFormType(SchemaInterface $schema): CreateFormTypeFileResponse
    {
        return (new CreateFormTypeFileUseCase())->execute(
            new CreateFormTypeFileRequest($schema)
        );
    }

    private function createEntity(SchemaInterface $schema): CreateEntityFileResponse
    {
        return (new CreateEntityFileUseCase())->execute(
            new CreateEntityFileRequest($schema)
        );
    }

    private function createGateway(SchemaInterface $schema, array $actions): CreateGatewayFileResponse
    {
        return (new CreateGatewayFileUseCase())->execute(
            new CreateGatewayFileRequest($schema, $actions)
        );
    }

    private function createConcreteGateway(SchemaInterface $schema, array $actions): ConcreteGatewayFileResponse
    {
        return (new CreateConcreteGatewayFileUseCase())->execute(
            new CreateConcreteGatewayFileRequest($schema, 'MySQL', $actions)
        );
    }

    private function createFactory(SchemaInterface $schema): CreateFactoryFileResponse
    {
        return (new CreateFactoryFileUseCase())->execute(
            new CreateFactoryFileRequest($schema)
        );
    }

    private function createRepository(SchemaInterface $schema, array $actions): CreateRepositoryFileResponse
    {
        return (new CreateRepositoryFileUseCase())->execute(
            new CreateRepositoryFileRequest($schema, $actions)
        );
    }

    private function createUseCases(SchemaInterface $schema, array $actions): CreateUseCaseFileResponse
    {
        return (new CreateUseCaseFileUseCase())->execute(
            new CreateUseCaseFileRequest($schema, $actions)
        );
    }

    private function createRequests(SchemaInterface $schema, array $actions): CreateRequestFileResponse
    {
        return (new CreateRequestFileUseCase())->execute(
            new CreateRequestFileRequest($schema, $actions)
        );
    }

    private function createResponses(SchemaInterface $schema, array $actions): CreateResponseFileResponse
    {
        return (new CreateResponseFileUseCase())->execute(
            new CreateResponseFileRequest($schema, $actions)
        );
    }

    private function createCommands(SchemaInterface $schema, array $actions): CreateCommandFileResponse
    {
        return (new CreateCommandFileUseCase())->execute(
            new CreateCommandFileRequest($schema, $actions)
        );
    }

    private function createTemplates(SchemaInterface $schema): CreateTemplateFileResponse
    {
        return (new CreateTemplateFileUseCase())->execute(
            new CreateTemplateFileRequest($schema)
        );
    }

    private function createJavascript(SchemaInterface $schema): CreateJavascriptFileResponse
    {
        return (new CreateJavascriptFileUseCase())->execute(
            new CreateJavascriptFileRequest($schema)
        );
    }
}
