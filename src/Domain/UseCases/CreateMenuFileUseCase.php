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

use FlexPHP\Generator\Domain\Builders\Config\MenuBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateMenuFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateMenuFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\Schema;

final class CreateMenuFileUseCase
{
    public function execute(CreateMenuFileRequest $request): CreateMenuFileResponse
    {
        $entities = [];

        foreach ($request->schemafiles as $schemafile) {
            $schema = Schema::fromFile($schemafile);
            $entities[$schema->name()] = $schema->icon();
        }

        $entity = new MenuBuilder($entities);
        $path = \sprintf('%1$s/../../tmp/skeleton/config', __DIR__);

        $writer = new PhpWriter($entity->build(), 'menu', $path);

        return new CreateMenuFileResponse($writer->save());
    }
}
