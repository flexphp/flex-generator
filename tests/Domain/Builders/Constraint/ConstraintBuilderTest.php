<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\Constraint\ConstraintBuilder;
use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;

class ConstraintBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $entity = 'Test';
        $properties = [
            'title' => (new RuleBuilder([
                'title' => [
                    'required' => true,
                    'pattern' => '/^[a-z_]*$/',
                ],
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

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
<?php declare(strict_types=1);

namespace Domain\Test\Constraint;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestConstraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    public function title(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
        ], \$constraints));
    }

    public function content(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotBlank(),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ], \$constraints));
    }

    public function createdAt(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new DateTime(),
        ], \$constraints));
    }
}

T), $render->build());
    }
}
