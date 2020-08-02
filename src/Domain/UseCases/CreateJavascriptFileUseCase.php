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
use FlexPHP\Generator\Domain\Builders\Javascript\JavascriptBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateJavascriptFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateJavascriptFileResponse;
use FlexPHP\Generator\Domain\Writers\JsWriter;

final class CreateJavascriptFileUseCase
{
    public function execute(CreateJavascriptFileRequest $request): CreateJavascriptFileResponse
    {
        $inflector = new Inflector();
        $filename = $inflector->jsName($request->schema->name());

        $factory = new JavascriptBuilder($request->schema);
        $path = \sprintf('%1$s/../../tmp/skeleton/public/js', __DIR__);
        $writer = new JsWriter($factory->build(), $filename, $path);

        return new CreateJavascriptFileResponse($writer->save());
    }
}
