<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Builders\FormType;

use FlexPHP\Generator\Domain\Builders\AbstractBuilder;
use FlexPHP\Schema\SchemaAttribute;
use FlexPHP\Schema\SchemaInterface;

class FormTypeBuilder extends AbstractBuilder
{
    public function __construct(SchemaInterface $schema)
    {
        $entity = $this->getInflector()->entity($schema->name());
        $item = $this->getInflector()->item($schema->name());
        $route = $this->getInflector()->route($schema->name());
        $fkFns = $this->getFkFunctions($schema->fkRelations());
        $fkRels = $this->getFkRelations($schema->fkRelations());
        $inputs = [];
        $properties = \array_reduce(
            $schema->attributes(),
            function (array $result, SchemaAttribute $property) use (&$inputs) {
                $name = $this->getInflector()->camelProperty($property->name());
                $result[$name] = $property;
                $inputs[$name] = $this->getInputType($property->dataType(), $property->type());

                return $result;
            },
            []
        );
        $header = self::getHeaderFile();

        parent::__construct(\compact('header', 'entity', 'item', 'properties', 'inputs', 'route', 'fkRels', 'fkFns'));
    }

    protected function getFileTemplate(): string
    {
        return 'FormType.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/FormType', parent::getPathTemplate());
    }

    protected function getInputType(string $dataType, ?string $type): string
    {
        $inputTypes = [
            'text' => 'Textarea',
            'file' => 'File',
            'password' => 'Password',
            'timezone' => 'Timezone',
            'smallint' => 'Integer',
            'integer' => 'Integer',
            'float' => 'Number',
            'double' => 'Number',
            'bool' => 'Checkbox',
            'boolean' => 'Checkbox',
            'date' => 'Datepicker',
            'date_immutable' => 'DateTime',
            'datetime' => 'Datetimepicker',
            'datetime_immutable' => 'DateTime',
            'datetimetz' => 'DateTime',
            'datetimetz_immutable' => 'DateTime',
            'time' => 'Timepicker',
            'time_immutable' => 'DateTime',
        ];

        if (!empty($inputTypes[$type])) {
            return $inputTypes[$type];
        }

        if (!empty($inputTypes[$dataType])) {
            return $inputTypes[$dataType];
        }

        return 'Text';
    }
}
