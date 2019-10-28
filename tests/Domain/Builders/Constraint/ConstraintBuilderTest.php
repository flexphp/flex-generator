<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Constraint\ConstraintBuilder;
use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ConstraintBuilderTest extends TestCase
{
    public function testItOk()
    {
        $render = new ConstraintBuilder([
            'entity' => 'Test',
            'properties' => [
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
            ],
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
<?php

namespace Domain\Test\Constraint;

/**
 * Constraints Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class TestConstraint
{
    public function title(array $constraints = [])
    {
        return $this->validator->validate(__FUNCTION__, [
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ]);
    }

    public function content(array $constraints = [])
    {
        return $this->validator->validate(__FUNCTION__, [
            new NotBlank(),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ]);
    }

    public function createdAt(array $constraints = [])
    {
        return $this->validator->validate(__FUNCTION__, [
            new DateTime(),
        ]);
    }
}

T), $render->build());
    }
}
