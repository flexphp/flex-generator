<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Exceptions\FieldSyntaxValidationException;
use FlexPHP\Schema\Constants\Keyword;

class FieldSyntaxValidation implements ValidationInterface
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    private $allowedProperties = [
        Keyword::NAME,
        Keyword::DATATYPE,
        Keyword::CONSTRAINTS,
        Keyword::TYPE,
    ];

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function validate(): void
    {
        // foreach ($this->properties as $property) {
        //     if (!\in_array($property, $this->allowedProperties)) {
        //         throw new FieldSyntaxValidationException('Property unknow: ' . $property);
        //     }
        // }
        \array_map(function ($property): void {
            if (!\in_array($property, $this->allowedProperties)) {
                throw new FieldSyntaxValidationException('Property unknow: ' . $property);
            }
        }, \array_keys($this->properties));
    }
}
