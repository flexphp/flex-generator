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

use FlexPHP\Messages\RequestInterface;

final class CreateDatabaseFileRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $platform;

    /**
     * @var string
     */
    public $dbname;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var array<int, string>
     */
    public $yamls;

    public function __construct(string $platform, string $dbname, string $username, string $password, array $yamls)
    {
        $this->platform = $platform;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->yamls = $yamls;
    }
}
