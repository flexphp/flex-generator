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
use FlexPHP\Generator\Domain\Builders\Template\TemplateBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateTemplateFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateTemplateFileResponse;
use FlexPHP\Generator\Domain\Writers\TemplateWriter;

final class CreateTemplateFileUseCase
{
    public function execute(CreateTemplateFileRequest $request): CreateTemplateFileResponse
    {
        $files = [];
        $inflector = new Inflector();
        $filename = $inflector->item($request->schema->name());
        $actions = [
            'index' => 'index.html',
            'create' => 'new.html',
            'read' => 'show.html',
            'update' => 'edit.html',
            'delete' => '_delete_form.html',
            'ajax' => '_ajax.html',
        ];

        foreach (\array_keys($actions) as $action) {
            if ($action !== 'ajax' && !$request->schema->hasAction(\substr($action, 0, 1))) {
                unset($actions[$action]);
            }
        }

        if (empty($actions['index'])) {
            unset($actions['ajax']);
        }

        $path = \sprintf('%1$s/../../tmp/skeleton/templates/%2$s', __DIR__, $filename);

        foreach ($actions as $action => $filename) {
            $builder = new TemplateBuilder($request->schema, $action);
            $writer = new TemplateWriter($builder->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateTemplateFileResponse($files);
    }
}
