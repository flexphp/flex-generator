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

final class CreatePrototypeRequest implements RequestInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $sheets;

    /**
     * @var string
     */
    public $outputDir;

    /**
     * @var string
     */
    public $platform;

    public function __construct(string $name, array $sheets, string $outputDir, string $platform = 'MySQL')
    {
        $this->name = $name;
        $this->sheets = $sheets;
        $this->outputDir = $outputDir;
        $this->platform = $platform;
    }
}
