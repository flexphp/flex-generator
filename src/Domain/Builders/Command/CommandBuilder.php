<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Command;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\Constants\Keyword;

final class CommandBuilder extends AbstractBuilder
{
    public function __construct(string $entity, string $action, array $properties)
    {
        $properties = \array_reduce($properties, function ($result, $property) {
            $result[$property[Keyword::NAME]] = $property;

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'action', 'properties'));
    }

    protected function getFileTemplate(): string
    {
        return 'Command.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Command', parent::getPathTemplate());
    }
}
