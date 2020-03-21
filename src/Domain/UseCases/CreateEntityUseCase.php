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

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityResponse;
use FlexPHP\UseCases\UseCase;

class CreateEntityUseCase extends UseCase
{
    /**
     * Create entity
     *
     * @param CreateEntityRequest $request
     *
     * @return CreateEntityResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateEntityRequest::class, $request);

        $name = $request->name;
        $properties = $request->properties;

        $entity = new EntityBuilder([
            'name' => $name,
            'properties' => $properties,
        ]);

        $dir = \sprintf('%1$s/../../tmp/skeleton/src/Domain/%2$s/Entity', __DIR__, $name);

        if (!\is_dir($dir)) {
            \mkdir($dir, 0777, true);
        }

        $file = \sprintf('%1$s/%2$s.php', $dir, $name);

        \file_put_contents($file, $entity->build());

        return new CreateEntityResponse($file);
    }
}
