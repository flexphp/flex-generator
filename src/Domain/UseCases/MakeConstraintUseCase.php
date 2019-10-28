<?php

namespace FlexPHP\Generator\Domain\UseCases;

use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Domain\Builders\Constraint\ConstraintBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\MakeConstraintRequest;
use FlexPHP\Generator\Domain\Messages\Responses\MakeConstraintResponse;
use FlexPHP\UseCases\UseCase;

class MakeConstraintUseCase extends UseCase
{
    /**
     * Create constraint attribute for entity
     *
     * @param MakeConstraintRequest $request
     * @return MakeConstraintResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, MakeConstraintRequest::class, $request);

        $entity = $request->entity;
        $properties = $request->properties;
        $_properties = [];
        
        foreach ($properties as $name => $constraint) {
            $_properties[$name] = (new RuleBuilder([
                $name => $constraint,
            ]))->build();
        }

        $constraint = new ConstraintBuilder([
            'entity' => $entity,
            'properties' => $_properties,
        ]);

        $dir = sprintf('%1$s/../../tmp/skeleton/src/Domain/%2$s/Constraint', __DIR__, $entity);

        if (!\is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = \sprintf('%1$s/%2$sConstraint.php', $dir, $entity);

        \file_put_contents($file, $constraint->build());

        return new MakeConstraintResponse($file);
    }
}
