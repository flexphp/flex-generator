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

use FlexPHP\Generator\Domain\Builders\Message\RequestBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateRequestFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateRequestFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;

final class CreateRequestFileUseCase
{
    use InflectorTrait;

    public function execute(CreateRequestFileRequest $request): CreateRequestFileResponse
    {
        $files = [];
        $entity = $this->getSingularize($request->entity);

        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s/Request', __DIR__, $entity);

        foreach ($request->actions as $action) {
            $builder = new RequestBuilder($entity, $action, $request->properties);
            $filename = $this->getPascalCase($action) . $entity . 'Request';

            $writer = new PhpWriter($builder->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateRequestFileResponse($files);
    }
}
