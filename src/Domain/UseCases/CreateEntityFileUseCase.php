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

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateEntityFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create entity file
     *
     * @param CreateEntityFileRequest $request
     *
     * @return CreateEntityFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateEntityFileRequest::class, $request);

        $name = $request->name;
        $properties = \array_reduce(
            $request->properties,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[$schemaAttribute->name()] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $entity = new EntityBuilder($name, $properties);
        $filename = $this->getSingularize($name);
        $path = \sprintf('%1$s/../../tmp/skeleton/src/Domain/%2$s/Entity', __DIR__, $name);

        $writer = new PhpWriter($entity->build(), $filename, $path);

        return new CreateEntityFileResponse($writer->save());
    }
}
