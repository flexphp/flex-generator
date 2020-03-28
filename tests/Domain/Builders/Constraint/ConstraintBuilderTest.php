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
use FlexPHP\Generator\Tests\TestCase;

final class ConstraintBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $entity = 'Test';

        $render = new ConstraintBuilder($entity, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Constraint;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestConstraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}

T, $render->build());
    }

    /**
     * @dataProvider getEntityName
     *
     * @param [type] $name
     */
    public function testItOkWithDiffNameEntity(string $name, string $expected): void
    {
        $render = new ConstraintBuilder($name, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected}\\Constraint;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class {$expected}Constraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}

T, $render->build());
    }

    public function testItOkWithDiffNameProperties(): void
    {
        $entity = 'Test';

        $render = new ConstraintBuilder($entity, $this->getSchemaPropertiesRules());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Constraint;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestConstraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    public function lower(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Length([
                'min' => 100,
            ]),
        ], \$constraints));
    }

    public function upper(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new LessThanOrEqual([
                'value' => 10,
            ]),
        ], \$constraints));
    }

    public function pascalCase(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotNull(),
            new NotBlank(),
        ], \$constraints));
    }

    public function camelCase(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
        ], \$constraints));
    }

    public function snakeCase(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new Length([
                'min' => 100,
                'max' => 200,
            ]),
        ], \$constraints));
    }
}

T, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
