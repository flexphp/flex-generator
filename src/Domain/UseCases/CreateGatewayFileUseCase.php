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
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Messages\Requests\CreateGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateGatewayFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;

final class CreateGatewayFileUseCase
{
    public function execute(CreateGatewayFileRequest $request): CreateGatewayFileResponse
    {
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());

        $gateway = new GatewayBuilder($request->schema, $request->actions);
        $filename = $entity . 'Gateway';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s', __DIR__, $entity);
        $writer = new PhpWriter($gateway->build(), $filename, $path);

        return new CreateGatewayFileResponse($writer->save());
    }
}
