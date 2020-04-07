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
use FlexPHP\UseCases\UseCase;

final class CreateFactoryFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create entity file
     *
     * @param CreateFactoryFileRequest $request
     *
     * @return CreateFactoryFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateFactoryFileRequest::class, $request);

        $entity = $this->getPascalCase($this->getSingularize($request->entity));

        $gateway = new FactoryBuilder($entity);
        $filename = $entity . 'Factory';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);
        $writer = new PhpWriter($gateway->build(), $filename, $path);

        return new CreateFactoryFileResponse($writer->save());
    }
}
