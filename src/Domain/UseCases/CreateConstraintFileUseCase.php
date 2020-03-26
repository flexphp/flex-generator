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

use FlexPHP\Generator\Domain\Builders\Constraint\ConstraintBuilder;
use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateConstraintFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConstraintFileResponse;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateConstraintFileUseCase extends UseCase
{
    /**
     * Create constraint attribute for entity
     *
     * @param CreateConstraintFileRequest $request
     *
     * @return CreateConstraintFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateConstraintFileRequest::class, $request);

        $entity = $request->entity;
        $properties = \array_reduce(
            $request->properties,
            function (array $result, SchemaAttributeInterface $schemaAttribute) {
                $name = $schemaAttribute->name();
                $result[$name] = (new RuleBuilder($name, $schemaAttribute->constraints()))->build();

                return $result;
            },
            []
        );

        $constraint = new ConstraintBuilder($entity, $properties);

        $dir = \sprintf('%1$s/../../tmp/skeleton/src/Domain/%2$s/Constraint', __DIR__, $entity);

        if (!\is_dir($dir)) {
            \mkdir($dir, 0777, true); // @codeCoverageIgnore
        }

        $file = \sprintf('%1$s/%2$sConstraint.php', $dir, $entity);

        \file_put_contents($file, $constraint->build());

        return new CreateConstraintFileResponse($file);
    }
}
