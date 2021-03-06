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

use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Builders\Repository\RepositoryBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateRepositoryFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRepositoryFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateRepositoryFileUseCase
{
    public function execute(CreateRepositoryFileRequest $request): CreateRepositoryFileResponse
    {
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());

        $repository = new RepositoryBuilder($request->schema, $request->actions);
        $filename = $entity . 'Repository';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);
        $writer = new PhpWriter($repository->build(), $filename, $path);

        return new CreateRepositoryFileResponse($writer->save());
    }
}
