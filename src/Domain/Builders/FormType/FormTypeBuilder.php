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
use FlexPHP\Schema\Constants\Keyword;
use Jawira\CaseConverter\Convert;

final class FormTypeBuilder extends AbstractBuilder
{
    public function __construct(string $entity, array $properties)
    {
        $entity = $this->getPascalCase($this->getSingularize($entity));
        $properties = \array_reduce($properties, function (array $result, array $property) {
            $result[$this->getCamelCase($property[Keyword::NAME])] = $property;

            return $result;
        }, []);

        $labels = \array_reduce($properties, function (array $result, array $property) {
            $result[] = (new Convert($property[Keyword::NAME]))->toTitle();

            return $result;
        }, []);

        $inputs = \array_reduce($properties, function (array $result, array $property) {
            $result[] = $this->getInputType($property[Keyword::DATATYPE]);

            return $result;
        }, []);

        parent::__construct(\compact('entity', 'properties', 'labels', 'inputs'));
    }

    protected function getFileTemplate(): string
    {
        return 'FormType.php.twig';
    }

    protected function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/FormType', parent::getPathTemplate());
    }

    private function getInputType(string $dataType): string
    {
        $inputTypes = [
            'text' => 'Textarea',
            'smallint' => 'Integer',
            'integer' => 'Integer',
            'float' => 'Number',
            'double' => 'Number',
            'bool' => 'Checkbox',
            'boolean' => 'Checkbox',
            'date' => 'Date',
            'date_immutable' => 'DateTime',
            'datetime' => 'DateTime',
            'datetime_immutable' => 'DateTime',
            'datetimetz' => 'DateTime',
            'datetimetz_immutable' => 'DateTime',
            'time' => 'Time',
            'time_immutable' => 'DateTime',
        ];

        if (!empty($inputTypes[$dataType])) {
            return $inputTypes[$dataType];
        }

        return 'Text';
    }
}
