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

use FlexPHP\Generator\Domain\Builders\Command\CommandBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateCommandFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateCommandFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;

final class CreateCommandFileUseCase
{
    use InflectorTrait;

    public function execute(CreateCommandFileRequest $request): CreateCommandFileResponse
    {
        $files = [];
        $entity = $this->getSingularize($request->entity);
        $actions = $request->actions;

        $properties = \array_reduce(
            $request->attributes,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $result[$schemaAttribute->name()] = $schemaAttribute->properties();

                return $result;
            },
            []
        );

        $path = \sprintf('%1$s/../../tmp/skeleton/src/Command/%2$s', __DIR__, $entity);

        foreach ($actions as $action) {
            $request = new CommandBuilder($entity, $action, $properties);
            $filename = $this->getPascalCase($action) . $entity . 'Command';

            $writer = new PhpWriter($request->build(), $filename, $path);
            $files[] = $writer->save();
        }

        return new CreateCommandFileResponse($files);
    }
}
