<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\Translate;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttributeInterface;
use FlexPHP\Schema\SchemaInterface;
use Jawira\CaseConverter\Convert;

final class TranslateBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $headers = [];
        $entity = (new Convert($this->getSingularize($schema->name())))->toTitle();
        $headers = \array_reduce($schema->attributes(), function (array $result, SchemaAttributeInterface $property) {
            $result[$this->getCamelCase($property->name())] = (new Convert($property->name()))->toTitle();

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'headers'));
    }

    protected function getFileTemplate(): string
    {
        return 'translate.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/Symfony/v43/templates', parent::getPathTemplate());
    }
}
