<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Gateway;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;

class GatewayBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['actions']) && \is_array($data['actions'])) {
            foreach ($data['actions'] as $index => $action) {
                $data['actions'][$index] = $this->getCamelCase($action);
            }
        }

        parent::__construct($data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Gateway.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Gateway', parent::getPathTemplate());
    }
}
