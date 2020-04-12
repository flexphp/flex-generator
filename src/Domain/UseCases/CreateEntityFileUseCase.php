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
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;

final class CreateEntityFileUseCase
{
    use InflectorTrait;

    public function execute(CreateEntityFileRequest $request): CreateEntityFileResponse
    {
        $name = $this->getSingularize($request->name);
        $properties = \array_reduce(
            $request->properties,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $entity = new EntityBuilder($name, $properties);
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $name);

        $writer = new PhpWriter($entity->build(), $name, $path);

        return new CreateEntityFileResponse($writer->save());
    }
}
