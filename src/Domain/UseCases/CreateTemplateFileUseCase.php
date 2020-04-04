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

use FlexPHP\Generator\Domain\Builders\Template\TemplateBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateTemplateFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTemplateFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\TemplateWriter;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateTemplateFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create Template files for action based in entity
     *
     * @param CreateTemplateFileRequest $request
     *
     * @return CreateTemplateFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateTemplateFileRequest::class, $request);

        $files = [];
        $entity = $this->getDashCase($this->getSingularize($request->entity));
        $actions = [
            'index' => 'index.html',
            'create' => 'new.html',
            'read' => 'show.html',
            'update' => 'edit.html',
            'delete' => '_delete_form.html',
        ];

        $properties = \array_reduce(
            $request->attributes,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[$schemaAttribute->name()] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $path = \sprintf('%1$s/../../tmp/skeleton/templates/%2$s', __DIR__, $entity);

        foreach ($actions as $action => $filename) {
            $request = new TemplateBuilder($entity, $action, $properties);
            $writer = new TemplateWriter($request->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateTemplateFileResponse($files);
    }
}
