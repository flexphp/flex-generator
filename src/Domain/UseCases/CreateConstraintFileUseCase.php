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
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\UseCases\UseCase;

final class CreateConstraintFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create constraint attribute file for entity
     *
     * @param CreateConstraintFileRequest $request
     *
     * @return CreateConstraintFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateConstraintFileRequest::class, $request);

        $entity = $this->getSingularize($request->entity);
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
        $filename = $entity . 'Constraint';
        $path = \sprintf('%1$s/Domain/%2$s/Constraint', $request->outputFolder, $entity);

        $writer = new PhpWriter($constraint->build(), $filename, $path);

        return new CreateConstraintFileResponse($writer->save());
    }
}
