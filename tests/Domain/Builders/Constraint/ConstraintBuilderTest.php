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
use FlexPHP\Schema\Schema;

final class ConstraintBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new ConstraintBuilder(new Schema('Test', 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestConstraint
{
    public function __construct(array \$data)
    {
        \$errors = [];

        foreach (\$data as \$key => \$value) {
            \$violations = \$this->getValidator()->validate(\$value, \$this->{\$key}());

            if (count(\$violations)) {
                \$errors[] = (string)\$violations;
            }
        }

        return \$errors;
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}

T
, $render->build());
    }

    public function testItAiAndBlameAt(): void
    {
        $render = new ConstraintBuilder($this->getSchemaAiAndBlameAt());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestConstraint
{
    public function __construct(array \$data)
    {
        \$errors = [];

        foreach (\$data as \$key => \$value) {
            \$violations = \$this->getValidator()->validate(\$value, \$this->{\$key}());

            if (count(\$violations)) {
                \$errors[] = (string)\$violations;
            }
        }

        return \$errors;
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    private function key(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }

    private function value(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $name, string $expected): void
    {
        $render = new ConstraintBuilder(new Schema($name, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class {$expected}Constraint
{
    public function __construct(array \$data)
    {
        \$errors = [];

        foreach (\$data as \$key => \$value) {
            \$violations = \$this->getValidator()->validate(\$value, \$this->{\$key}());

            if (count(\$violations)) {
                \$errors[] = (string)\$violations;
            }
        }

        return \$errors;
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}

T
, $render->build());
    }

    public function testItOkWithDiffNameProperties(): void
    {
        $render = new ConstraintBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestConstraint
{
    public function __construct(array \$data)
    {
        \$errors = [];

        foreach (\$data as \$key => \$value) {
            \$violations = \$this->getValidator()->validate(\$value, \$this->{\$key}());

            if (count(\$violations)) {
                \$errors[] = (string)\$violations;
            }
        }

        return \$errors;
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    private function lower(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 20,
                'max' => 100,
            ]),
        ];
    }

    private function upper(): array
    {
        return [
            new Assert\Type([
                'type' => 'int',
            ]),
            new Assert\LessThanOrEqual([
                'value' => 2,
            ]),
            new Assert\GreaterThanOrEqual([
                'value' => 10,
            ]),
        ];
    }

    private function pascalCase(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\DateTime(),
        ];
    }

    private function camelCase(): array
    {
        return [
            new Assert\Type([
                'type' => 'bool',
            ]),
        ];
    }

    private function snakeCase(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 100,
                'max' => 200,
            ]),
        ];
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
