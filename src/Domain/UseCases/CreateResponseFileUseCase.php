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
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateResponseFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create request message file based in attributes entity
     *
     * @param CreateResponseFileRequest $request
     *
     * @return CreateResponseFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateResponseFileRequest::class, $request);

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

        $path = \sprintf('%1$s/Domain/%2$s/Response', $request->outputFolder, $entity);

        foreach ($actions as $action) {
            $request = new ResponseBuilder($entity, $action, $properties);
            $filename = $this->getPascalCase($action) . $entity . 'Response';

            $writer = new PhpWriter($request->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateResponseFileResponse($files);
    }
}
