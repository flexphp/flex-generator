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
use FlexPHP\UseCases\UseCase;

final class CreateRequestFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create request message file based in attributes entity
     *
     * @param CreateRequestFileRequest $request
     *
     * @return CreateRequestFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateRequestFileRequest::class, $request);

        $files = [];
        $entity = $this->getSingularize($request->entity);
        $actions = $request->actions;
        $properties = \array_reduce(
            $request->properties,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $name = $schemaAttribute->name();
                $result[$name] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $path = \sprintf('%1$s/../../tmp/skeleton/src/Requests', __DIR__);

        foreach ($actions as $action) {
            $request = new RequestBuilder($entity, $action, $properties);
            $filename = $this->getPascalCase($action) . $entity . 'Request';

            $writer = new PhpWriter($request->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateRequestFileResponse($files);
    }
}
