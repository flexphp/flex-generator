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

use FlexPHP\Generator\Domain\Builders\Factory\FactoryBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateFactoryFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateFactoryFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;

final class CreateFactoryFileUseCase
{
    use InflectorTrait;

    public function execute(CreateFactoryFileRequest $request): CreateFactoryFileResponse
    {
        $entity = $this->getPascalCase($this->getSingularize($request->schema->name()));

        $factory = new FactoryBuilder($entity, $request->schema);
        $filename = $entity . 'Factory';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);
        $writer = new PhpWriter($factory->build(), $filename, $path);

        return new CreateFactoryFileResponse($writer->save());
    }
}
