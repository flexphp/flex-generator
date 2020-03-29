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
use FlexPHP\Generator\Domain\Messages\Requests\CreateUseCaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConstraintFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateControllerFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
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
        $outputFolder = $request->outputFolder;
        $actions = [
            'index',
            'create',
            'read',
            'update',
            'delete',
        ];

        $controller = $this->createController($name, $actions, $outputFolder);
        $entity = $this->createEntity($name, $attributes, $outputFolder);
        $constraint = $this->createConstraint($name, $attributes, $outputFolder);
        $requests = $this->createRequest($name, $actions, $attributes, $outputFolder);
        $useCases = $this->createUseCases($name, $actions, $attributes, $outputFolder);

        return new SheetProcessResponse([
            'controller' => $controller->file,
            'entity' => $entity->file,
            'constraint' => $constraint->file,
            'requests' => $requests->files,
            'useCases' => $useCases,
        ]);
    }

    private function createController(string $name, array $actions, string $outputFolder): CreateControllerFileResponse
    {
        return (new CreateControllerFileUseCase())->execute(
            new CreateControllerFileRequest($name, $actions, $outputFolder)
        );
    }

    private function createConstraint(string $name, array $attributes, string $outputFolder): CreateConstraintFileResponse
    {
        return (new CreateConstraintFileUseCase())->execute(
            new CreateConstraintFileRequest($name, $attributes, $outputFolder)
        );
    }

    private function createEntity(string $name, array $attributes, string $outputFolder): CreateEntityFileResponse
    {
        return (new CreateEntityFileUseCase())->execute(
            new CreateEntityFileRequest($name, $attributes, $outputFolder)
        );
    }

    private function createUseCases(string $name, array $actions, array $attributes, string $outputFolder): array
    {
        return \array_reduce(
            $actions,
            function (array $result, string $action) use ($name, $attributes, $outputFolder): array {
                $response = (new CreateUseCaseFileUseCase())->execute(
                    new CreateUseCaseFileRequest($name, $action, $attributes, $outputFolder)
                );

                $result[] = $response->file;

                return $result;
            },
            []
        );
    }

    private function createRequest(string $name, array $actions, array $attributes, string $outputFolder): CreateRequestFileResponse
    {
        return (new CreateRequestFileUseCase())->execute(
            new CreateRequestFileRequest($name, $attributes, $actions, $outputFolder)
        );
    }
}
