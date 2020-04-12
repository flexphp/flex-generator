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

use FlexPHP\Generator\Domain\Builders\Message\ResponseBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateResponseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateResponseFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateResponseFileUseCase
{
    use InflectorTrait;

    /**
     * Create request message file based in attributes entity
     *
     * @param  $request
     *
     * @return
     */
    public function execute(CreateResponseFileRequest $request): CreateResponseFileResponse
    {
        $files = [];
        $entity = $this->getSingularize($request->entity);
        $actions = $request->actions;

        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s/Response', __DIR__, $entity);

        foreach ($actions as $action) {
            $request = new ResponseBuilder($entity, $action);
            $filename = $this->getPascalCase($action) . $entity . 'Response';

            $writer = new PhpWriter($request->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateResponseFileResponse($files);
    }
}
