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

use FlexPHP\Generator\Domain\Builders\Gateway\GatewayBuilder;
use FlexPHP\Generator\Domain\Messages\Requests\CreateGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateGatewayFileResponse;
use FlexPHP\Generator\Domain\Traits\InflectorTrait;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use FlexPHP\UseCases\UseCase;

final class CreateGatewayFileUseCase extends UseCase
{
    use InflectorTrait;

    /**
     * Create entity file
     *
     * @param CreateGatewayFileRequest $request
     *
     * @return CreateGatewayFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateGatewayFileRequest::class, $request);

        $entity = $this->getPascalCase($this->getSingularize($request->entity));

        $gateway = new GatewayBuilder($entity, $request->actions);
        $filename = $entity . 'Gateway';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);
        $writer = new PhpWriter($gateway->build(), $filename, $path);

        return new CreateGatewayFileResponse($writer->save());
    }
}
