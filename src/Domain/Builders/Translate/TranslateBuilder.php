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

final class TranslateBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $titleSingular = $this->getInflector()->entityTitleSingular($schema->name());
        $titlePlural = $this->getInflector()->entityTitlePlural($schema->name());
        $headers = \array_reduce($schema->attributes(), function (array $result, SchemaAttributeInterface $property) {
            $result[
                $this->getInflector()->camelProperty($property->name())
            ] = $this->getInflector()->propertyTitle($property->name());

            return $result;
        }, []);

        parent::__construct(\compact('titleSingular', 'titlePlural', 'headers'));
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
