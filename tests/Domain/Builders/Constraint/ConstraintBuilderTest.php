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

class ConstraintBuilderTest extends TestCase
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

class TestConstraint
{
    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}

T, $render->build());
    }
}
