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
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateEntityFileUseCase
{
    public function execute(CreateEntityFileRequest $request): CreateEntityFileResponse
    {
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());

        $builder = new EntityBuilder($request->schema);
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);

        $writer = new PhpWriter($builder->build(), $entity, $path);

        return new CreateEntityFileResponse($writer->save());
    }
}
