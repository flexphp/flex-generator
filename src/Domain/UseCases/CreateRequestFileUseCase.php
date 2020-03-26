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
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;
use Jawira\CaseConverter\Convert;
use Symfony\Component\Inflector\Inflector;

final class CreateRequestFileUseCase extends UseCase
{
    /**
     * Create request message file based in properties
     *
     * @param CreateRequestFileRequest $request
     *
     * @return CreateRequestFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateRequestFileRequest::class, $request);

        $files = [];
        $entity = Inflector::singularize($request->entity);
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

        $dir = \sprintf('%1$s/../../tmp/skeleton/src/Requests', __DIR__);

        if (!\is_dir($dir)) {
            \mkdir($dir, 0777, true); // @codeCoverageIgnore
        }

        foreach ($actions as $action) {
            $request = new RequestBuilder($entity, $action, $properties);

            $file = \sprintf('%1$s/%2$s%3$sRequest.php', $dir, (new Convert($action))->toPascal(), $entity);

            \file_put_contents($file, $request->build());

            $files[] = $file;
        }

        return new CreateRequestFileResponse($files);
    }
}
