<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Messages\Responses;

use FlexPHP\Messages\ResponseInterface;

final class SheetProcessResponse implements ResponseInterface
{
    /**
     * @var array
     */
    public $response;

    /**
     * @var null|string
     */
    public $controller;

    /**
     * @var null|string
     */
    public $constraint;

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->controller = $response['controller'] ?? null;
        $this->constraint = $response['constraint'] ?? null;
    }
}
