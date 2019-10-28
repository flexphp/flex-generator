<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Constraint\ConstraintBuilder;
use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ConstraintBuilderTest extends TestCase
{
    public function testItOk()
    {
        $entity = 'Test';
        $properties = [
            'title' => (new RuleBuilder([
                'title' => [
                    'required' => true,
                    'pattern' => '/^[a-z_]*$/',
                ]
            ]))->build(),
            'content' => (new RuleBuilder([
                'content' => [
                    'required' => true,
                    'length' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
            ]))->build(),
            'createdAt' => (new RuleBuilder([
                'createdAt' => [
                    'type' => 'datetime',
                ],
            ]))->build(),
            ];

        $render = new ConstraintBuilder([
            'entity' => $entity,
            'properties' => $properties,
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
<?php

namespace Domain\Test\Constraint;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Constraints Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class TestConstraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    public function title(array $constraints = [])
    {
        return $this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ], $constraints));
    }

    public function content(array $constraints = [])
    {
        return $this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotBlank(),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ], $constraints));
    }

    public function createdAt(array $constraints = [])
    {
        return $this->getValidator()->validate(__FUNCTION__, array_merge([
            new DateTime(),
        ], $constraints));
    }
}

T), $render->build());
    }
}
