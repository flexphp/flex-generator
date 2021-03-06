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
use FlexPHP\Generator\Domain\Builders\Message\FkResponseBuilder;
use FlexPHP\Generator\Domain\Builders\Message\ResponseBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateResponseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateResponseFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateResponseFileUseCase
{
    public function execute(CreateResponseFileRequest $request): CreateResponseFileResponse
    {
        $files = [];
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());
        $actions = $request->actions;

        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s/Response', __DIR__, $entity);

        foreach ($actions as $action) {
            $response = new ResponseBuilder($request->schema, $action);
            $filename = $inflector->pascalAction($action) . $entity . 'Response';

            $writer = new PhpWriter($response->build(), $filename, $path);
            $files[] = $writer->save();
        }

        foreach ($request->schema->fkRelations() as $fkRel) {
            $fkEntity = $inflector->entity($fkRel['pkTable']);
            $builder = new FkResponseBuilder($request->schema->name(), $fkEntity);
            $filename = 'Find' . $entity . $fkEntity . 'Response';

            $writer = new PhpWriter($builder->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateResponseFileResponse($files);
    }
}
