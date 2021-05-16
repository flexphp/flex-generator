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

use FlexPHP\Generator\Domain\Builders\Gateway\MySQLGatewayBuilder;
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Messages\Requests\CreateConcreteGatewayFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateConcreteGatewayFileResponse;
use FlexPHP\Generator\Domain\Writers\PhpWriter;
use InvalidArgumentException;

final class CreateConcreteGatewayFileUseCase
{
    /**
     * @var array<string, string>
     */
    private array $concretes = [
        'MySQL' => 'MySQLGatewayBuilder',
    ];

    public function execute(CreateConcreteGatewayFileRequest $request): CreateConcreteGatewayFileResponse
    {
        $inflector = new Inflector();
        $entity = $inflector->entity($request->schema->name());
        $concrete = $request->concrete;
        $concretesAvailable = \array_keys($this->concretes);

        if (!\in_array($concrete, $concretesAvailable)) {
            throw new InvalidArgumentException($concrete . ' is not valid, use: ' . \implode(',', $concretesAvailable));
        }

        $gateway = new MySQLGatewayBuilder($request->schema, $request->actions);
        $filename = $concrete . $entity . 'Gateway';
        $path = \sprintf('%1$s/../../tmp/skeleton/domain/%2$s/Gateway', __DIR__, $entity);
        $writer = new PhpWriter($gateway->build(), $filename, $path);

        return new CreateConcreteGatewayFileResponse($writer->save());
    }
}
