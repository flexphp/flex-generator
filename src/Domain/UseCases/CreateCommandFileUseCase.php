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

use FlexPHP\Generator\Domain\Builders\Command\CommandBuilder;
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Messages\Requests\CreateCommandFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateCommandFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateCommandFileUseCase
{
    public function execute(CreateCommandFileRequest $request): CreateCommandFileResponse
    {
        $files = [];
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());

        $path = \sprintf('%1$s/../../tmp/skeleton/src/Command/%2$s', __DIR__, $entity);
        $actions = \array_diff($request->actions, ['login', 'filter']);

        foreach ($actions as $action) {
            $builder = new CommandBuilder($request->schema, $action);
            $filename = $inflector->pascalAction($action) . $entity . 'Command';

            $writer = new PhpWriter($builder->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateCommandFileResponse($files);
    }
}
