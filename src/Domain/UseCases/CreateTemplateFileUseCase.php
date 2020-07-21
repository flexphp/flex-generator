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

final class CreateTemplateFileUseCase
{
    use InflectorTrait;

    public function execute(CreateTemplateFileRequest $request): CreateTemplateFileResponse
    {
        $files = [];
        $entity = $this->getDashCase($this->getSingularize($request->schema->name()));
        $actions = [
            'index' => 'index.html',
            'create' => 'new.html',
            'read' => 'show.html',
            'update' => 'edit.html',
            'delete' => '_delete_form.html',
        ];

        $path = \sprintf('%1$s/../../tmp/skeleton/templates/%2$s', __DIR__, $entity);

        foreach ($actions as $action => $filename) {
            $builder = new TemplateBuilder($request->schema, $action);
            $writer = new TemplateWriter($builder->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateTemplateFileResponse($files);
    }
}
