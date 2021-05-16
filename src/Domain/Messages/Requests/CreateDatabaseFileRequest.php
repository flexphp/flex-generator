<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Messages\Requests;

final class CreateDatabaseFileRequest
{
    public string $platform;

    public string $dbname;

    public string $username;

    public string $password;

    /**
     * @var array<int, string>
     */
    public array $yamls;

    public function __construct(string $platform, string $dbname, string $username, string $password, array $yamls)
    {
        $this->platform = $platform;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->yamls = $yamls;
    }
}
