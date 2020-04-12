<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\FormType;

use FlexPHP\Generator\Domain\Builders\FormType\FormTypeBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class FormTypeBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new FormTypeBuilder('Test', []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
    }
}

T
, $render->build());
    }

    public function testItOkWithDiffNameProperties(): void
    {
        $render = new FormTypeBuilder('Test', $this->getSchemaProperties());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('lower', InputType\TextType::class, [
            'label' => 'Lower',
            'required' => false,
            'attr' => [
                'minlength' => 20,
                'maxlength' => 100,
            ],
        ]);
        \$builder->add('upper', InputType\IntegerType::class, [
            'label' => 'Upper',
            'required' => false,
            'attr' => [
                'min' => 2,
                'max' => 10,
            ],
        ]);
        \$builder->add('pascalCase', InputType\DateTimeType::class, [
            'label' => 'Pascal Case',
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'format' => 'Y-m-d H:i:s',
        ]);
        \$builder->add('camelCase', InputType\CheckboxType::class, [
            'label' => 'Camel Case',
            'required' => false,
        ]);
        \$builder->add('snakeCase', InputType\TextareaType::class, [
            'label' => 'Snake Case',
            'required' => false,
            'attr' => [
                'minlength' => 100,
                'maxlength' => 200,
            ],
        ]);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expected): void
    {
        $render = new FormTypeBuilder($entity, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class {$expected}FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
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
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
