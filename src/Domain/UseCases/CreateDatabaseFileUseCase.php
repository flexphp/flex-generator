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

use FlexPHP\Database\Builder;
use FlexPHP\Generator\Domain\Builders\Inflector;
use FlexPHP\Generator\Domain\Messages\Requests\CreateDatabaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateDatabaseFileResponse;
use FlexPHP\Generator\Domain\Writers\SqlWriter;
use FlexPHP\Schema\Schema;

final class CreateDatabaseFileUseCase
{
    public function execute(CreateDatabaseFileRequest  $request): CreateDatabaseFileResponse
    {
        $builder = new Builder($request->platform);

        \array_map(function (string $schemafile) use ($builder): void {
            $builder->createTable(Schema::fromFile($schemafile));
        }, $request->yamls);

        $path = \sprintf('%1$s/../../tmp/skeleton/domain/Database', __DIR__);

        $writer = new SqlWriter($builder->toSql(), 'database', $path);

        return new CreateDatabaseFileResponse($writer->save());
    }
}
