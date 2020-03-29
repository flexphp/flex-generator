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

use FlexPHP\Generator\Domain\Builders\UseCase\UseCaseBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateUseCaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateUseCaseFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateUseCaseFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create use case file for action based in entity
     *
     * @param CreateUseCaseFileRequest $request
     *
     * @return CreateUseCaseFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateUseCaseFileRequest::class, $request);

        $entity = $request->entity;
        $action = $request->action;
        $properties = \array_reduce(
            $request->properties,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[$schemaAttribute->name()] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $useCase = new UseCaseBuilder($entity, $action, $properties);
        $filename = $this->getPascalCase($action) . $this->getSingularize($entity) . 'UseCase';
        $path = \sprintf('%1$s/Domain/UseCases', $request->outputFolder);

        $writer = new PhpWriter($useCase->build(), $filename, $path);

        return new CreateUseCaseFileResponse($writer->save());
    }
}
