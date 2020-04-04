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
use FlexPHP\Generator\Domain\Messages\Requests\CreateDatabaseFileRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateDatabaseFileResponse;
use FlexPHP\Generator\Domain\Writers\SqlWriter;
use FlexPHP\Schema\Schema;
use FlexPHP\UseCases\UseCase;

final class CreateDatabaseFileUseCase extends UseCase
{
    /**
     * Create database file
     *
     * @param CreateDatabaseFileRequest $request
     *
     * @return CreateDatabaseFileResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreateDatabaseFileRequest::class, $request);

        $builder = new Builder($request->platform);
        $builder->createDatabase($request->dbname);
        $builder->createUser($request->username, $request->password);

        \array_map(function (string $schemafile) use ($builder): void {
            $builder->createTable(Schema::fromFile($schemafile));
        }, $request->yamls);

        $path = \sprintf('%1$s/../../tmp/skeleton/domain/database/', __DIR__);

        $writer = new SqlWriter($builder->toSql(), 'database', $path);

        return new CreateDatabaseFileResponse($writer->save());
    }
}
