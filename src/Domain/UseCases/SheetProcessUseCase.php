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

use FlexPHP\Generator\Domain\Messages\Requests\CreateConstraintFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateControllerFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateRequestFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateResponseFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreateUseCaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConstraintFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateControllerFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateResponseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateUseCaseFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\Schema\Schema;
use FlexPHP\UseCases\UseCase;

final class SheetProcessUseCase extends UseCase
{
    /**
     * Process sheet
     *
     * @param SheetProcessRequest $request
     *
     * @return SheetProcessResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, SheetProcessRequest::class, $request);

        $name = $request->name;
        $attributes = Schema::fromFile($request->path)->attributes();
        $actions = [
            'index',
            'create',
            'read',
            'update',
            'delete',
        ];

        $controller = $this->createController($name, $actions);
        $entity = $this->createEntity($name, $attributes);
        $constraint = $this->createConstraint($name, $attributes);
        $requests = $this->createRequests($name, $actions, $attributes);
        $responses = $this->createResponses($name, $actions);
        $useCases = $this->createUseCases($name, $actions, $attributes);

        return new SheetProcessResponse([
            'controller' => $controller->file,
            'entity' => $entity->file,
            'constraint' => $constraint->file,
            'requests' => $requests->files,
            'responses' => $responses->files,
            'useCases' => $useCases->files,
        ]);
    }

    private function createController(string $name, array $actions): CreateControllerFileResponse
    {
        return (new CreateControllerFileUseCase())->execute(
            new CreateControllerFileRequest($name, $actions)
        );
    }

    private function createConstraint(string $name, array $attributes): CreateConstraintFileResponse
    {
        return (new CreateConstraintFileUseCase())->execute(
            new CreateConstraintFileRequest($name, $attributes)
        );
    }

    private function createEntity(string $name, array $attributes): CreateEntityFileResponse
    {
        return (new CreateEntityFileUseCase())->execute(
            new CreateEntityFileRequest($name, $attributes)
        );
    }

    private function createUseCases(string $name, array $actions, array $attributes): CreateUseCaseFileResponse
    {
        return (new CreateUseCaseFileUseCase())->execute(
            new CreateUseCaseFileRequest($name, $actions, $attributes)
        );
    }

    private function createRequests(string $name, array $actions, array $attributes): CreateRequestFileResponse
    {
        return (new CreateRequestFileUseCase())->execute(
            new CreateRequestFileRequest($name, $attributes, $actions)
        );
    }

    private function createResponses(string $name, array $actions): CreateResponseFileResponse
    {
        return (new CreateResponseFileUseCase())->execute(
            new CreateResponseFileRequest($name, $actions)
        );
    }
}
