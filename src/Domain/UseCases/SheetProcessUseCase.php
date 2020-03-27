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
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConstraintFileResponse;
use FlexPHP\Generator\Domain\Messages\Responses\CreateControllerFileResponse;
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
        $outputFolder = $request->outputFolder;

        $controller = $this->makeController($name, $outputFolder);
        $constraint = $this->makeConstraint($name, Schema::fromFile($request->path)->attributes(), $outputFolder);

        return new SheetProcessResponse([
            'controller' => $controller->file,
            'constraint' => $constraint->file,
        ]);
    }

    private function makeController(string $name, string $outputFolder): CreateControllerFileResponse
    {
        return (new CreateControllerFileUseCase())->execute(
            new CreateControllerFileRequest($name, [
                'index',
                'create',
                'read',
                'update',
                'delete',
            ], $outputFolder)
        );
    }

    private function makeConstraint(string $name, array $properties, string $outputFolder): CreateConstraintFileResponse
    {
        return (new CreateConstraintFileUseCase())->execute(
            new CreateConstraintFileRequest($name, $properties, $outputFolder)
        );
    }
}
